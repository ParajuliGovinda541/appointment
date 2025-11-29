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
        $Visitor = $this->findById($id);
        return $Visitor->update(['status' => 'Active']);
    }

    public function deactivate($id)
    {
        $Visitor = $this->findById($id);

        if ($Visitor->officers()->where('status', 'Active')->exists()) {
            return false;
        }

        return $Visitor->update(['status' => 'Inactive']);
    }
}
