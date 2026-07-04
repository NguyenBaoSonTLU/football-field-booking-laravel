<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FootballFieldStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFootballFieldRequest;
use App\Http\Requests\Admin\UpdateFootballFieldRequest;
use App\Models\FootballField;
use App\Services\FootballFieldService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FootballFieldController extends Controller
{
    public function __construct(private readonly FootballFieldService $footballFieldService)
    {
    }

    public function index(Request $request): View
    {
        $keyword = $request->string('keyword')->trim()->toString();
        $fields = FootballField::query()
            ->with(['mainImage'])
            ->withCount('bookings')
            ->search($keyword)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.fields.index', compact('fields', 'keyword'));
    }

    public function create(): View
    {
        $statuses = FootballFieldStatus::cases();

        return view('admin.fields.create', compact('statuses'));
    }

    public function store(StoreFootballFieldRequest $request): RedirectResponse
    {
        $field = $this->footballFieldService->create($request->validated());

        return redirect()->route('admin.fields.edit', $field)
            ->with('success', 'Đã thêm sân bóng mới.');
    }

    public function show(FootballField $field): View
    {
        $field->load(['images', 'bookings.user', 'bookings.timeSlot']);

        return view('admin.fields.show', compact('field'));
    }

    public function edit(FootballField $field): View
    {
        $field->load('images');
        $statuses = FootballFieldStatus::cases();

        return view('admin.fields.edit', compact('field', 'statuses'));
    }

    public function update(UpdateFootballFieldRequest $request, FootballField $field): RedirectResponse
    {
        $this->footballFieldService->update($field, $request->validated());

        return back()->with('success', 'Thông tin sân bóng đã được cập nhật.');
    }

    public function destroy(FootballField $field): RedirectResponse
    {
        $deleted = $this->footballFieldService->deleteOrDeactivate($field);

        return redirect()->route('admin.fields.index')->with(
            'success',
            $deleted
                ? 'Đã xóa sân bóng.'
                : 'Sân đã có lịch sử đặt nên được chuyển sang trạng thái ngừng hoạt động.'
        );
    }
}
