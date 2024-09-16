{{ $request->title }}
{{ \Carbon\Carbon::parse($request->from_date)->format('d.m.Y H:i') }} ({{ $request->duration() }})
{{ $request->user->first_name }} {{ $request->user->surname }} - {{ $request->city }}
https://babysitter-app.com/my-applies/{{ $request->id }}
