<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Évaluations du professeur</h2>
            <a href="{{ route('teachers.evaluations.create', ['teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle évaluation
            </a>
        </div>
    </div>
    
    <div class="p-6">
        @if($teacher->evaluations->count() > 0)
        <div class="space-y-4">
            @foreach($teacher->evaluations as $evaluation)
            <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-medium text-white">
                            {{ ucfirst($evaluation->evaluation_type) }} - {{ $evaluation->evaluation_date->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Évaluateur: {{ $evaluation->evaluator->name ?? 'Non spécifié' }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-lg font-bold text-white">
                            {{ number_format($evaluation->overall_rating, 1) }}/10
                        </div>
                        @php
                            $ratingColor = match(true) {
                                $evaluation->overall_rating >= 8 => 'text-green-400',
                                $evaluation->overall_rating >= 6 => 'text-yellow-400',
                                default => 'text-red-400',
                            };
                        @endphp
                        <div class="w-10 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-600 rounded-full" 
                                 style="width: {{ $evaluation->overall_rating * 10 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Notes détaillées -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                    <div class="text-center p-2 bg-gray-800 rounded">
                        <div class="text-xs text-gray-400">Pédagogie</div>
                        <div class="text-sm font-medium text-white">{{ $evaluation->pedagogical_skills }}/10</div>
                    </div>
                    <div class="text-center p-2 bg-gray-800 rounded">
                        <div class="text-xs text-gray-400">Connaissance</div>
                        <div class="text-sm font-medium text-white">{{ $evaluation->subject_knowledge }}/10</div>
                    </div>
                    <div class="text-center p-2 bg-gray-800 rounded">
                        <div class="text-xs text-gray-400">Gestion classe</div>
                        <div class="text-sm font-medium text-white">{{ $evaluation->classroom_management }}/10</div>
                    </div>
                    <div class="text-center p-2 bg-gray-800 rounded">
                        <div class="text-xs text-gray-400">Communication</div>
                        <div class="text-sm font-medium text-white">{{ $evaluation->communication }}/10</div>
                    </div>
                    <div class="text-center p-2 bg-gray-800 rounded">
                        <div class="text-xs text-gray-400">Ponctualité</div>
                        <div class="text-sm font-medium text-white">{{ $evaluation->punctuality }}/10</div>
                    </div>
                </div>
                
                <!-- Commentaires -->
                <div class="space-y-2">
                    @if($evaluation->strengths)
                    <div>
                        <div class="text-xs font-medium text-gray-400">Points forts</div>
                        <div class="text-sm text-gray-300 mt-1">{{ $evaluation->strengths }}</div>
                    </div>
                    @endif
                    
                    @if($evaluation->improvements_needed)
                    <div>
                        <div class="text-xs font-medium text-gray-400">Axes d'amélioration</div>
                        <div class="text-sm text-gray-300 mt-1">{{ $evaluation->improvements_needed }}</div>
                    </div>
                    @endif
                    
                    @if($evaluation->recommendations)
                    <div>
                        <div class="text-xs font-medium text-gray-400">Recommandations</div>
                        <div class="text-sm text-gray-300 mt-1">{{ $evaluation->recommendations }}</div>
                    </div>
                    @endif
                </div>
                
                <!-- Document -->
                @if($evaluation->document_path)
                <div class="mt-3 pt-3 border-t border-gray-800">
                    <a href="{{ Storage::url($evaluation->document_path) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 text-sm text-primary-400 hover:text-primary-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Document d'évaluation
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500 mb-4">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">Aucune évaluation</h3>
            <p class="text-sm text-gray-500 mb-6">Aucune évaluation n'a été enregistrée pour ce professeur</p>
            <a href="{{ route('teachers.evaluations.create', ['teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une évaluation
            </a>
        </div>
        @endif
    </div>
</div>