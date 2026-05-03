@extends('layouts.app')

@section('content')
<header class="page-header">
    <p class="page-kicker">Visit Or Call</p>
    <h1>Contact Steam & Spice</h1>
    <p class="page-lead">From weekday dinners to weekend celebrations, our team is here to help with bookings, takeaway questions, and special requests.</p>
</header>

<style>
    .page-header { 
        margin-top: 24px; 
    }
    .contact-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:32px;
        margin-top:32px;
    }
    .contact-card{
        background:linear-gradient(165deg, rgba(43,29,20,0.96), rgba(26,18,11,0.92));
        color:#f5f5f5;
        border:1px solid rgba(241,200,118,0.18);
        border-radius:14px;
        padding:24px;
        box-shadow:0 8px 18px rgba(0,0,0,0.26);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }
    .contact-card:hover{
        transform: translateY(-4px);
        box-shadow:0 12px 30px rgba(0,0,0,0.3), 0 0 20px rgba(255, 123, 50, 0.12);
        border-color: rgba(241,200,118,0.24);
    }
    .contact-card h2{color:#f5f5f5}
    .contact-card p{color:#cfcfcf}
    .contact-title{
        margin:0 0 12px;
        font-family: 'Playfair Display', Georgia, serif;
        font-weight:700;
        font-size: clamp(1.15rem, 2.2vw, 1.35rem);
        color:#f5f5f5;
        letter-spacing: -0.2px;
    }
    .contact-sub{
        margin:0;
        color:#cfcfcf;
        font-size: 0.95rem;
        line-height: 1.7;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }
    .contact-list{
        display:flex;
        flex-direction:column;
        gap:18px;
        margin-top:20px;
    }
    .contact-item{
        display:flex;
        gap:14px;
        align-items:flex-start;
    }
    .contact-icon{
        width:40px;
        height:40px;
        flex:0 0 40px;
        border-radius:10px;
        background:linear-gradient(135deg,#ff7b32,#f1c876);
        display:inline-grid;
        place-items:center;
        color:#1a120b;
        font-weight:700;
    }
    .contact-item .text{
        display:flex;
        flex-direction:column;
        gap:2px;
    }
    .contact-item .label{
        font-weight:700;
        color:#f5f5f5;
        font-size: 0.92rem;
        font-family: 'Playfair Display', Georgia, serif;
    }
    .contact-item .value{
        color:#cfcfcf;
        font-size: 0.93rem;
        line-height: 1.6;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .map-wrap{
        border-radius:12px;
        overflow:hidden;
        border:1px solid rgba(241,200,118,0.14);
        box-shadow:0 10px 26px rgba(0,0,0,0.26);
    }
    .map-iframe{
        width:100%;
        height:100%;
        min-height:320px;
        border:0;
        display:block;
    }

    @media(max-width:880px){
        .contact-grid{
            grid-template-columns:1fr;
            gap:24px;
        }
        .map-iframe{
            min-height:260px;
        }
    }
</style>

<div class="contact-grid">
    <aside class="contact-card">
        <h2 class="contact-title">Contact</h2>
        <p class="contact-sub">We're here to help — reservations, orders, and enquiries.</p>

        <div class="contact-list">
            <div class="contact-item">
                <div class="contact-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 16.5V19a2 2 0 0 1-2 2c-7.18 0-13-5.82-13-13A2 2 0 0 1 8 4h2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="text">
                    <span class="label">Phone</span>
                    <span class="value">{{ $siteSettings->phone ?? '+44 20 1234 5678' }}</span>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8.5a4 4 0 0 1 4-4h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 15.5a4 4 0 0 1-4 4H7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="text">
                    <span class="label">Email</span>
                    <span class="value">{{ $siteSettings->email ?? 'hello@steamandspice.co.uk' }}</span>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 10c0 6-9 11-9 11s-9-5-9-11a9 9 0 1 1 18 0z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="text">
                    <span class="label">Address</span>
                    <span class="value">{{ $siteSettings->address ?? '221B Baker Street, London, UK' }}</span>
                </div>
            </div>
        </div>
    </aside>

    <div class="map-wrap">
        <iframe class="map-iframe"
            src="https://www.google.com/maps?q={{ urlencode($siteSettings->address ?? 'London') }}&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>
@endsection
