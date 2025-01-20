<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notice\StoreRequest;
use App\Http\Requests\Notice\UpdateRequest;
use App\Http\Resources\NoticeResource;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $fee_type = $request->input('fee_type');
        $notice_type = $request->input('notice_type');
        $type = $request->input('type');
        $start_date = $request->start_date ? date('Y-m-d',strtotime($request->start_date)) : null;
        $end_date = $request->end_date ? date('Y-m-d',strtotime($request->end_date)) : null;

        $query = Notice::latest();

        if (!is_null($search)){
            $query->where('name','like',"%{$search}%");
        }
        if (!is_null($fee_type)){
            $query->where('fee_type',$fee_type);
        }
        if (!is_null($notice_type)){
            $query->where('notice_type',$notice_type);
        }
        if (!is_null($type)){
            $query->where('type',$type);
        }
        if (!is_null($start_date)){
            $query->whereBetween('created_at',[$start_date,$end_date]);
        }

        $query = $query->paginate(10)->withQueryString();
        $notices = NoticeResource::collection($query);

        return inertia()->render('Notice/Index',['notices' => $notices,'filters' => [
            'search'=> $search,
            'fee_type'=> $fee_type,
            'notice_type'=> $notice_type,
            'type'=> $type,
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ]]);
    }


    public function store(StoreRequest $request)
    {
        $notice = Notice::create($this->storeData($request));
        if (!$notice){
            return back()->with('error', 'Notice Not Created Successfully');
        }

        return back()->with('success', 'Notice Created Successfully');

    }

    public function update($id,UpdateRequest $request)
    {
        $notice = Notice::find($id);
        $notice->update($this->storeData($request,$notice));

        return back()->with('success', 'Notice Updated Successfully');

    }
    public function destroy($id)
    {
        $notice = Notice::find($id);

        if ($notice?->file !== null && Storage::disk('public')->exists($notice?->file)){
            Storage::disk('public')->delete($notice?->file);
        }
        $notice->delete();

        return back()->with('success', 'Notice deleted Successfully');

    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function storeData($request,$notice = null): array
    {
        if ($request->file){
            if ($notice !== null && $notice->file !== null && Storage::disk('public')->exists($notice->file)){
                Storage::disk('public')->delete($notice?->file);
            }
            return $request->only('name', 'body','status')

                + ['file' => $request->file->storePublicly(
                    'notices',
                    ['disk' => 'public']
                ),
                    'file_size' => $request->file->getSize(),
                    'file_type' => $request->file->extension(),
                    'user_id' => auth()->id()
                    ];
        }
        return $request->only('name', 'body','status')+['user_id' => auth()->id()];
    }
}
