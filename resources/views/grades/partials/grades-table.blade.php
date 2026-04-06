{{-- resources/views/grades/partials/grades-table.blade.php --}}
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                @foreach($subjects as $subject)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $subject->name }}
                        <span class="block text-xs">Coef: {{ $subject->coefficient }}</span>
                    </th>
                @endforeach
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moyenne</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appréciation</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($students as $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                    </td>
                    @foreach($subjects as $subject)
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $grade = $grades[$student->id][$subject->id] ?? null;
                            @endphp
                            @if($grade)
                                <div class="text-sm {{ $grade->score >= ($grade->max_score * 0.5) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($grade->score, 2) }}/{{ $grade->max_score }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Coef: {{ $grade->coefficient }}
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $average = $student->calculateAverage($subjects, $grades[$student->id] ?? []);
                        @endphp
                        <div class="text-sm font-semibold {{ $average >= 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($average, 2) }}/20
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $student->getAppreciation($average) }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>