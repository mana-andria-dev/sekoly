<div class="space-y-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-lg font-semibold text-white">Matières assignées</h3>
            <p class="text-sm text-gray-400">Gestion des matières et professeurs pour cette classe</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="#"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Assigner des matières
            </a>
            <a href="#"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvelle affectation
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="text-2xl font-bold text-white">{{ $class->assignments()->count() }}</div>
            <div class="text-sm text-gray-400">Matières assignées</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="text-2xl font-bold text-green-500">
                {{ $class->assignments()->where('is_active', true)->count() }}
            </div>
            <div class="text-sm text-gray-400">Matières actives</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="text-2xl font-bold text-blue-500">
                {{ $class->assignments()->sum('hours_per_week') }}
            </div>
            <div class="text-sm text-gray-400">Heures/semaine</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="text-2xl font-bold text-purple-500">
                {{ $class->assignments()->whereNotNull('teacher_id')->distinct('teacher_id')->count() }}
            </div>
            <div class="text-sm text-gray-400">Professeurs</div>
        </div>
    </div>

    <!-- Table des affectations -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        @if($class->assignments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Matière
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Professeur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Heures/Sem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Coef.
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($class->assignments()->with(['subject', 'teacher'])->get() as $assignment)
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-success/10 rounded-lg flex items-center justify-center">
                                    <span class="text-success">📚</span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $assignment->subject->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->subject->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($assignment->teacher)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-warning/10 rounded-full flex items-center justify-center">
                                    <span class="text-warning text-xs">👨‍🏫</span>
                                </div>
                                <div>
                                    <div class="text-sm text-white">{{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->teacher->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500 text-sm italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600/10 text-blue-400">
                                {{ $assignment->hours_per_week }}h
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-600/10 text-purple-400">
                                {{ $assignment->coefficient }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            
                        </td>
                        <td class="px-6 py-4">

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500 mb-4">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">Aucune matière assignée</h3>
            <p class="text-sm text-gray-500 mb-6">Commencez par assigner des matières à cette classe</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Assigner en masse
                </a>
                <a href="#"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white font-medium transition-all">
                    Assigner une matière
                </a>
            </div>
        </div>
        @endif
    </div>
</div>