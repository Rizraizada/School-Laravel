@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Contact</h3>
        <div class="panel-body">
            <div class="two-col">
                <article class="mini-card">
                    <h4>School Office Address</h4>
                    <p>
                        Bharasar High School<br>
                        Bharasar, Comilla<br>
                        Bangladesh
                    </p>
                </article>
                <article class="mini-card">
                    <h4>Phone & E-mail</h4>
                    <p>Phone: +880-0000-000000</p>
                    <p>Email: info@bharasarhighschool.edu.bd</p>
                    <p>Office Hours: 9:00 AM - 5:00 PM</p>
                </article>
            </div>
        </div>
    </section>

    <section class="panel">
        <h3 class="panel-header">Service Desks</h3>
        <div class="panel-body portal-table-wrap">
            <table class="portal-table">
                <thead>
                <tr>
                    <th>Desk</th>
                    <th>Purpose</th>
                    <th>Contact Channel</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Notice & Publication Cell</td>
                    <td>Official school notice publishing and archive support</td>
                    <td>notice@bharasarhighschool.edu.bd</td>
                </tr>
                <tr>
                    <td>Student Help Desk</td>
                    <td>Student communication, forms and certificate support</td>
                    <td>helpdesk@bharasarhighschool.edu.bd</td>
                </tr>
                <tr>
                    <td>Technical Support</td>
                    <td>Online portal and account assistance</td>
                    <td>support@bharasarhighschool.edu.bd</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>

    @if(isset($branches) && $branches->isNotEmpty())
        <section class="panel">
            <h3 class="panel-header">Branch Directory</h3>
            <div class="panel-body portal-table-wrap">
                <table class="portal-table">
                    <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Incharge</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $branch->branch_name }}</td>
                            <td>{{ $branch->branch_incharge ?: '-' }}</td>
                            <td>{{ $branch->branch_address ?: '-' }}</td>
                            <td>{{ $branch->branch_phone ?: '-' }}</td>
                            <td>{{ $branch->branch_email ?: '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
