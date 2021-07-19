<?php

namespace App\Exports;

use App\Models\KetQuaTuyenSinh;
use Maatwebsite\Excel\Concerns\FromCollection;

class ListTrungTuyenTheoPTExport implements FromCollection
{
    public function __construct(String $pt = null)
    {
        $this->pt = $pt;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $tuyenthang = KetQuaTuyenSinh::join('hoso_phuongthuc','ketquatuyensinh.mahspt','=','hoso_phuongthuc.mahspt')
                                            ->join('hoso','hoso_phuongthuc.mahoso','=','hoso.mahoso')
                                            ->join('phuongthucxettuyen','hoso_phuongthuc.maphuongthuc','=','phuongthucxettuyen.maphuongthuc')
                                            ->where('phuongthucxettuyen.maphuongthuc',3)
                                            ->join('nganh','ketquatuyensinh.manganh','=','nganh.manganh')
                                            ->select('hoso.mahoso','hoso.hoten','phuongthucxettuyen.tenphuongthuc','manguyenvong','nganh.tennganh','tongdiem')
                                            ->get();

        $kq = KetQuaTuyenSinh::join('hoso_phuongthuc','ketquatuyensinh.mahspt','=','hoso_phuongthuc.mahspt')
                            ->join('hoso','hoso_phuongthuc.mahoso','=','hoso.mahoso')
                            ->join('phuongthucxettuyen','hoso_phuongthuc.maphuongthuc','=','phuongthucxettuyen.maphuongthuc')
                            ->join('nganh','ketquatuyensinh.manganh','=','nganh.manganh')
                            ->join('khoithi','ketquatuyensinh.makhoi','=','khoithi.makhoi')
                            ->join('diemchuan', function($join){
                                $join->on('hoso_phuongthuc.maphuongthuc','=','diemchuan.maphuongthuc')
                                    ->on('diemchuan.manganh','=','ketquatuyensinh.manganh')
                                    ->whereColumn('diemchuan.diemchuan','<','ketquatuyensinh.tongdiem');
                            })
                            ->where('hoso_phuongthuc.maphuongthuc','=',$this->pt)
                            ->select('hoso.mahoso','hoso.hoten','phuongthucxettuyen.tenphuongthuc','manguyenvong','nganh.tennganh','khoithi.tenkhoi','tongdiem')
                            ->get();

        if($this->pt == 3){
            return $tuyenthang;
        } else{
            return $kq;
        }
    }
}
