<div>

    <style>
        .gradient-text {
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 50%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-icon {
            @apply w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-indigo-600 dark:text-indigo-400;
            /* background-color: rgba(99, 102, 241, 0.1); */
            color:white;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .dark .hero-gradient {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        }
    </style>

    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold gradient-text">Student Buddy</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('home') }}"
                                class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Log
                                in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center">
                <h1
                    class="text-2xl tracking-tight font-semibold text-gray-900 dark:text-white sm:text-5xl md:text-5xl">
                    <span class="block text-black">AI-Powered Learning</span>
                    <span class="block text-lackindigo-600 dark:text-indigo-600">for Students</span>
                </h1>
                <p
                    class="mt-3 max-w-md mx-auto text-sm text-gray-500 dark:text-gray-600 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Upload your lecture notes, get instant summaries, and ask questions about your study materials.
                    Powered by AI to help you learn smarter, not harder.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="{{ route('home') }}"
                            class="flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-2 md:text-lg md:px-4">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-2 md:text-lg md:px-4">
                            Get Started for Free
                        </a>
                        <a href="#features"
                            class="flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium border-2 rounded-md text-indigo-700   dark:text-indigo-600 dark:border-indigo-600 dark:hover:bg-indigo-600 md:py-2 md:text-lg md:px-4">
                            Learn More
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">
                    Features</h2>
                <p
                    class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    A better way to study
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-300 lg:mx-auto">
                    Student Buddy helps you understand and retain information more effectively.
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div
                        class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Smart Summaries</h3>
                        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                            Get concise, AI-generated summaries of your PDF notes with key points highlighted for quick
                            review.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Instant Q&A</h3>
                        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                            Ask questions about your study materials and get accurate, context-aware answers powered by
                            AI.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="relative p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Organized Notes</h3>
                        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                            Keep all your study materials organized in one place with an intuitive, easy-to-use
                            interface.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700 dark:bg-indigo-900">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Ready to transform your study habits?</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Join thousands of students who are already learning more effectively with Student Buddy.
            </p>
            <div class="mt-8 flex justify-center">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Get started for free
                    </a>
                </div>
            </div>
        </div>
    </div>



</div>
