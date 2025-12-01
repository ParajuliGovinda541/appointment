<?php

namespace App\Services;

use App\Models\Visitor;
use Carbon\Carbon;

/**
 * Class VisitorService.
 */
class VisitorService
{



    public function getAll()
    {
        return Visitor::orderBy('id', 'desc')->get();
    }

    public function create(array $data)
    {
        return Visitor::create([
            'name' => $data['name'],
            'mobile_no' => $data['mobile_no'],
            'email' => $data['email'],
            'status' => 'Active',
        ]);
    }

    public function update(Visitor $Visitor, array $data)
    {
        return $Visitor->update([
            'name' => $data['name'],
            'mobile_no' => $data['mobile_no'],
            'email' => $data['email'],
            'status' => 'Active',
        ]);
    }

    public function findById($id)
    {
        return Visitor::findOrFail($id);
    }


    public function activate($id)
    {
        $visitor = Visitor::findOrFail($id);

        // Activate the visitor
        $visitor->update(['status' => 'Active']);

        // Reactivate related future appointments that are deactivated, but only if officer is active
        $visitor->appointments()
            ->where('date', '>', Carbon::today())
            ->where('status', 'Inactive')
            ->whereHas('officer', function ($q) {
                $q->where('status', 'Active');
            })
            ->update(['status' => 'Active']);

        return [
            'success' => true,
            'message' => 'Visitor activated successfully.'
        ];
    }

    public function deactivate($id)
    {
        $visitor = Visitor::findOrFail($id);

        $visitor->update(['status' => 'Inactive']);

        $visitor->appointments()
            ->where('date', '>', Carbon::today())
            ->where('status', 'Active')
            ->update(['status' => 'Inactive']);

        return [
            'success' => true,
            'message' => 'Visitor Deactiviated successfully.'
        ];
    }
}
