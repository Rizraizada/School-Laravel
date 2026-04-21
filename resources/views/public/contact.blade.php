@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Contact</h3>
        <div class="panel-body">
            <div class="two-col">
                <article class="mini-card">
                    <h4>Board Office Address</h4>
                    <p>
                        School Management Board Office<br>
                        Kandirpar Administrative Zone<br>
                        Cumilla, Bangladesh
                    </p>
                </article>
                <article class="mini-card">
                    <h4>Phone & E-mail</h4>
                    <p>Phone: +880-0000-000000</p>
                    <p>Email: info@schoolboard.gov.bd</p>
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
                    <td>Official notice publishing and archive support</td>
                    <td>notice@schoolboard.gov.bd</td>
                </tr>
                <tr>
                    <td>Institution Help Desk</td>
                    <td>Institution communication, forms and corrections</td>
                    <td>helpdesk@schoolboard.gov.bd</td>
                </tr>
                <tr>
                    <td>Technical Support</td>
                    <td>Online service portal and account assistance</td>
                    <td>support@schoolboard.gov.bd</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
