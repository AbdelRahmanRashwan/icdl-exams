<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\SkillsCard;
use Illuminate\Http\Request;

class SkillsCardController extends Controller
{
    public function index()
    {
        $paginate = \request()->paginate;
        $builder = $this->applyFilters(SkillsCard::orderBy('used', 'asc')->orderBy('number', 'asc')->with('category'));
        if ($paginate){
            $response = $builder->paginate($paginate);
        }
        else{
            $response = $builder->get();
        }
        return $this->respond('All Records Fetched Successfully', $response);
    }

    public function store()
    {
        if(SkillsCard::where('number', request()->number)->first()){
            return $this->respond('Skills card number already exists', [], 422);
        }

        return $this->respond('Created Successfully', SkillsCard::create(request()->all()));
    }

    public function update($id)
    {
        $skills_card = SkillsCard::find($id);

        $skills_card->update(request()->all());

        if (\request()->used == 0){
            $candidate = Candidate::where('skills_card_id', $id)->first();
            if ($candidate){
                $candidate->skills_card_id = null;
                $candidate->save();
            }
        }

        return $this->respond('Updated Successfully');
    }

    public function destroy($id)
    {
        SkillsCard::find($id)->delete();

        return $this->respond('Deleted Successfully');
    }

    public function applyFilters($builder)
    {
        if (\request()->category_id){
            $builder->categoryElements(\request()->category_id);
        }
        if (\request()->unused){
            $builder->unused();
        }
        return $builder;
    }

    public function range()
    {
        $first = \request()->first;
        $second = \request()->second;
        $start = \request()->start;
        $range = \request()->range;
        $skills = array();
        for ($i = 0; $i < $range; $i++){
            $skills []= \App\SkillsCard::create([
                'number' => "$first $second $start",
                'category_id' => \request()->category_id,
                'used' => 0
            ]);
            $start++;
        }
        return $this->respond('Created Successfully', $skills);
    }
}
