{{-- resources/views/tenant/exams/grades.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">Notes d'examens</h1>
        <p class="text-gray-400 text-sm mt-1">Ces notes sont utilisées pour le calcul des moyennes dans les bulletins</p>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Examen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Coef.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examResults as $result)
                    <tr class="hover:bg-gray-850">
                        <td class="px-6 py-4">{{ $result->student->first_name }} {{ $result->student->last_name }}</td>
                        <td class="px-6 py-4">{{ $result->exam->title }}</td>
                        <td class="px-6 py-4">{{ $result->exam->subject->name }}</td>
                        <td class="px-6 py-4">
                            <span class="{{ $result->score >= ($result->exam->max_score / 2) ? 'text-green-400' : 'text-red-400' }}">
                                {{ $result->score }}/{{ $result->exam->max_score }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $result->exam->coefficient }}</td>
                        <td class="px-6 py-4">{{ $result->exam->exam_date->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection