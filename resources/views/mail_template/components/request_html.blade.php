<a href="https://babysitter-app.com/my-applies/{{ $request->id }}" target="_blank" style="text-decoration: none">
<div style="padding: 10px; display: flex; align-items: center;">
    <!-- Thumbnail Image -->
    <div style="flex-shrink: 0; margin-right: 10px; cursor: pointer;">
        <img alt="{{ $request->user->first_name }} {{ $request->user->surname }}"
             src="{{ $request->user->image ? url($request->user->image) : url('assets/website/images/no-image-available.png') }}"
             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
    </div>

    <!-- Content -->
    <div style="flex-grow: 1;">
        <h3 style="margin: 0; font-size: 16px; color: #333;">{{ $request->title }}</h3>
        <p style="margin: 5px 0; font-size: 14px; color: #555;">
            {{ \Carbon\Carbon::parse($request->from_date)->format('d.m.Y H:i') }}&nbsp;({{ $request->duration() }})<br>
            {{ $request->user->first_name }}&nbsp;{{ $request->user->surname }}&nbsp;&ndash;&nbsp;{{ $request->city }}
        </p>
    </div>

</div>
</a>
