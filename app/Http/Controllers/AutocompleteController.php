<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutocompleteController extends Controller
{

    private $_q;

    public function search(Request $request)
    {

        $v = $request->validate([
            'search' => 'required',
            //'q' => 'required'
        ]);

        $select = null;
        $this->_q = $request->q;

        if ($request->search === 'region') {

            $select = $this->_search('bids', ['region', 'region_reg']);

        } else if ($request->search === 'localitate') {

            $select = $this->_search('bids', ['localitate', 'localitate_reg']);

        } else if ($request->search === 'street') {

            $select = $this->_search('clients', ['street', 'street_reg']);

        } else if ($request->search === 'buletin_office') {

            $select = $this->_search('bids', 'buletin_office');

        } else if ($request->search === 'first_name') {

            $select = $this->_search('bids', ['first_name', 'first_name_cont_pers1', 'first_name_cont_pers2'], true);

        } else if ($request->search === 'last_name') {

            $select = $this->_search('bids', ['last_name', 'last_name_cont_pers1', 'last_name_cont_pers2'], true);

        } else if ($request->search === 'who_is_cont_pers') {

            $select = $this->_search('bids', ['who_is_cont_pers1', 'who_is_cont_pers2'], true);

        } else if ($request->search === 'client_produs') {

            $select = $this->_search('bids', ['produs'], true);

        }

        return response()->json([
            'success' => true,
            'data' => $select
        ]);
    }

    private function _search($tableName, $col, $lookInDeletedRows = false)
    {
        if (is_string($col)) {
            $col = [$col];
        }

        $select = DB::table($tableName)->select($col[0] . ' as text')->whereNotNull($col[0]);
        if ($lookInDeletedRows) {
            $select = $select->whereNull('deleted');
        }
        if (!empty($this->_q)) {
            $select = $select->where($col[0], 'LIKE', $this->_q.'%');
        }
        $select = $select->groupBy($col[0])->distinct()->orderBy($col[0], 'asc'  )->limit(10);
        if (count($col) > 1) {
            unset($col[0]);
            foreach ($col as $columnName) {
                $select2 = DB::table($tableName)->select($columnName . ' as text')->whereNotNull($columnName);
                if ($lookInDeletedRows) {
                    $select2 = $select2->whereNull('deleted');
                }
                if (!empty($this->_q)) {
                    $select2 = $select2->where($columnName, 'LIKE', $this->_q.'%');
                }
                $select2 = $select2->groupBy($columnName)->distinct()->orderBy($columnName, 'asc'  )->limit(10);
                $select = $select->unionAll($select2);
            }
            $select = $select->groupBy('text');
        }
        // dd($select->toSql(), $col, $this->_q);
        $select = $select->distinct()->paginate(10);
        return $select;
/*
        if (is_array($col) && count($col) === 2){

            $select = DB::table($tableName)
                ->select($col[0] . ' as text')
                ->whereNotNull($col[0]);
            if ($lookInDeletedRows) {
                $select = $select->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select = $select->where($col[0], 'LIKE', $this->_q.'%');
            }
            $select = $select->groupBy($col[0])->orderBy($col[0], 'asc'  );

            $select2 = DB::table($tableName)
                ->select($col[1] . ' as text')
                ->whereNotNull($col[1]);
            if ($lookInDeletedRows) {
                $select2 = $select2->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select2 = $select2->where($col[1], 'LIKE', $this->_q.'%');
            }
            $select2 = $select2->groupBy($col[1])->orderBy($col[1], 'asc'  );

            $select = $select->unionAll($select2)->distinct()->paginate(30);

        } else if (is_array($col) && count($col) === 3){

            $select = DB::table($tableName)
                ->select($col[0] . ' as text')
                ->whereNotNull($col[0]);
            if ($lookInDeletedRows) {
                $select = $select->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select = $select->where($col[0], 'LIKE', $this->_q.'%');
            }
            $select = $select->groupBy($col[0])->orderBy($col[0], 'asc'  );

            $select2 = DB::table($tableName)
                ->select($col[1] . ' as text')
                ->whereNotNull($col[1]);
            if ($lookInDeletedRows) {
                $select2 = $select2->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select2 = $select2->where($col[1], 'LIKE', $this->_q.'%');
            }
            $select2 = $select2->groupBy($col[1])->orderBy($col[1], 'asc'  );

            $select3 = DB::table($tableName)
                ->select($col[2] . ' as text')
                ->whereNotNull($col[2]);
            if ($lookInDeletedRows) {
                $select3 = $select3->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select3 = $select3->where($col[2], 'LIKE', $this->_q.'%');
            }
            $select3 = $select3->groupBy($col[2])->orderBy($col[2], 'asc'  );

            $select = $select->unionAll($select2)->unionAll($select3)->distinct();

                $select = $select->paginate(30);

        } else {

            $select = DB::table($tableName)
                ->select($col . ' as text')
                ->whereNotNull($col);
            if ($lookInDeletedRows) {
                $select = $select->whereNull('deleted');
            }
            if (!empty($this->_q)) {
                $select = $select->where($col, 'LIKE', $this->_q.'%');
            }
            $select = $select->groupBy($col)
                ->orderBy($col, 'asc'  )
                ->paginate(30);
        }

        return $select;
*/
    }
}
