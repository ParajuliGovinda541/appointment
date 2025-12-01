<?php

namespace App\Services;

use App\Models\Visitor;

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
        return Visitor::findOrFail($id)->update(['status' => 'Active']);
    }

    public function deactivate($id)
    {
        return Visitor::findOrFail($id)->update(['status' => 'Inactive']);
    }
}
