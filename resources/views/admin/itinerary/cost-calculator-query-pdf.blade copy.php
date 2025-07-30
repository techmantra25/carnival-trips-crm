
@extends('layouts.master')
@section('title', $common['pageTitle'])
@section('content')

<div class="md:flex block items-center justify-between mb-6 mt-1  page-header-breadcrumb"><div class="my-auto">
        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0 uppercase">{{$common['childHeader']}}</h5>
        <nav>
            <ol class="flex items-center whitespace-nowrap min-w-0">
                <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                        href="javascript:void(0);"> {{$common['parentHeader']}} <i
                            class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                    </a> </li>
                <li class="text-[12px]"> <a class="flex items-center text-textmuted" href="javascript:void(0);">{{$common['childHeader']}}
                    </a> </li>
            </ol>
        </nav>
    </div>
        <a href="{{route('admin.cost_calculator.download_pdf')}}" style="background-color: #0162e8; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            font-size: 14px; 
            border-radius: 8px; 
            cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            transition: background-color 0.3s ease;">Download</a>
    </div>
</div>



<table class="table" id="print-section">
    <tr>
        <td>
            <table>
                <tr>
                    <td style=" vertical-align:top;">
                        <p>
                            <h4 style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 12px; text-transform: uppercase; border-radius: 5px; padding:4px;">Destination</h4>
                        </p>
                        <span>Andaman</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection
