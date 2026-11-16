<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $enquiries = $this->filtered($request)->with('project')->latest()->paginate(15)->withQueryString();
        $counts = [
            'all' => Enquiry::count(),
            'unread' => Enquiry::unread()->count(),
            'read' => Enquiry::where('is_read', true)->count(),
        ];

        return view('admin.enquiries.index', compact('enquiries', 'counts'));
    }

    public function show(Enquiry $enquiry)
    {
        if (!$enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }
        $enquiry->load('project');

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function toggleRead(Enquiry $enquiry)
    {
        $enquiry->update(['is_read' => !$enquiry->is_read]);

        return back()->with('success', 'Marked as ' . ($enquiry->is_read ? 'read' : 'unread') . '.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|in:read,unread,delete',
        ]);

        $q = Enquiry::whereIn('id', $data['ids']);
        match ($data['action']) {
            'read' => $q->update(['is_read' => true]),
            'unread' => $q->update(['is_read' => false]),
            'delete' => $q->delete(),
        };

        return back()->with('success', count($data['ids']) . ' enquiries updated.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->latest()->get();
        $filename = 'aiyra-enquiries-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Name', 'Phone', 'Email', 'Service', 'Location', 'Message', 'Source', 'Status']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->created_at->format('d-m-Y H:i'), $r->name, $r->phone, $r->email, $r->service,
                    $r->location, $r->message, $r->source, $r->is_read ? 'Read' : 'Unread',
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function filtered(Request $request)
    {
        return Enquiry::query()
            ->when($request->status === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($request->status === 'read', fn ($q) => $q->where('is_read', true))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")->orWhere('message', 'like', "%$s%")));
    }
}
