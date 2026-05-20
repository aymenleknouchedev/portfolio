@extends('layouts.admin')

@section('page_title', isset($addon) ? 'Edit Add-on' : 'New Add-on')
@section('page_subtitle', isset($addon) ? $addon->name : 'Create a new digital product')

@section('content')
<form action="{{ isset($addon) ? route('admin.addons.update', $addon) : route('admin.addons.store') }}"
    method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if(isset($addon)) @method('PUT') @endif

    {{-- Errors --}}
    @if($errors->any())
    <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-5 text-red-300 text-sm">
        <div class="font-semibold mb-2 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> Please fix the following:
        </div>
        <ul class="list-disc list-inside space-y-1 text-red-200/90">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT COLUMN (main) --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- General --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-500/15 flex items-center justify-center">
                        <i class="fa-solid fa-info text-purple-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">General</h2>
                        <p class="text-xs text-gray-500">Name, category & description shown on the product page.</p>
                    </div>
                </header>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Name</label>
                            <input type="text" name="name" value="{{ old('name', $addon->name ?? '') }}" required
                                placeholder="e.g. Procedural Smoke Generator"
                                class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none placeholder-gray-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Category</label>
                            <select name="category_id" required class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none">
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $addon->category_id ?? '') == $cat->id ? 'selected' : '' }} class="bg-gray-950">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="addon-description" rows="5"
                            class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description', $addon->description ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Badge text <span class="text-gray-600 normal-case">— optional tag shown on the product card</span></label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $addon->badge_text ?? '') }}" placeholder="e.g. Made for Blender"
                            class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none placeholder-gray-600">
                    </div>
                </div>
            </section>

            {{-- Pricing --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                        <i class="fa-solid fa-dollar-sign text-emerald-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Pricing</h2>
                        <p class="text-xs text-gray-500">Base price shown in the shop. Set $0 for a free add-on.</p>
                    </div>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Offer Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">$</span>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $addon->price ?? '') }}" required
                                class="w-full bg-gray-950 border border-white/10 rounded-xl pl-9 pr-4 py-3 text-white focus:border-purple-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Original Price <span class="text-gray-600 normal-case">— leave empty for no discount</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">$</span>
                            <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $addon->original_price ?? '') }}" placeholder="49.99"
                                class="w-full bg-gray-950 border border-white/10 rounded-xl pl-9 pr-4 py-3 text-white focus:border-purple-500 focus:outline-none placeholder-gray-600">
                        </div>
                    </div>
                </div>
            </section>

            {{-- License Packs --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6"
                x-data="{ requiresLicense: {{ old('requires_license', $addon->requires_license ?? true) ? 'true' : 'false' }} }">
                <header class="mb-5 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/15 flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-purple-300 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="font-semibold">License Packs</h2>
                            <p class="text-xs text-gray-500">Define how many licenses each pack delivers and at what price.</p>
                        </div>
                    </div>
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none px-3 py-2 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="requires_license" value="1" x-model="requiresLicense"
                            {{ old('requires_license', $addon->requires_license ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                        <span class="text-xs text-gray-300">Requires license</span>
                    </label>
                </header>

                <div x-show="!requiresLicense" x-cloak class="rounded-xl bg-white/5 border border-white/5 px-5 py-4 text-sm text-gray-400 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-gray-500 mt-0.5"></i>
                    <div>This product doesn't issue license keys (e.g. templates, presets). Clients pay the base price and download directly.</div>
                </div>

                <div x-show="requiresLicense" x-transition>
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-xs text-gray-500">
                            <i class="fa-solid fa-lightbulb text-amber-400/80 mr-1"></i>
                            If only <strong class="text-gray-300">one pack</strong> is defined, it's auto-applied at checkout. Multiple packs → clients pick.
                        </p>
                        <button type="button" id="add-tier-btn"
                            class="inline-flex items-center gap-1.5 text-xs bg-purple-600 hover:bg-purple-500 text-white font-medium px-3 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-purple-500/25">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Pack
                        </button>
                    </div>

                    <div id="license-tiers-list" class="space-y-3"></div>

                    <input type="hidden" name="license_tiers" id="license_tiers_input">
                </div>
            </section>

            {{-- Media --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/15 flex items-center justify-center">
                        <i class="fa-solid fa-image text-sky-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Media</h2>
                        <p class="text-xs text-gray-500">Cover image, demo video, and screenshots.</p>
                    </div>
                </header>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Cover Image</label>
                            @if(isset($addon) && $addon->cover_image)
                            <div class="mb-3 relative w-full">
                                <img src="{{ asset('storage/' . $addon->cover_image) }}" alt="Cover" class="w-full h-32 rounded-xl object-cover border border-white/10">
                                <div class="absolute bottom-2 left-2 text-[10px] text-white/80 bg-black/60 backdrop-blur-sm px-2 py-1 rounded">Current cover</div>
                            </div>
                            @endif
                            <input type="file" name="cover_image" accept="image/*"
                                class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none file:bg-purple-600 file:hover:bg-purple-500 file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3 file:cursor-pointer file:text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">YouTube Demo URL</label>
                            <div class="relative">
                                <i class="fa-brands fa-youtube absolute left-4 top-1/2 -translate-y-1/2 text-red-400 text-sm pointer-events-none"></i>
                                <input type="text" name="demo_video_url" value="{{ old('demo_video_url', $addon->demo_video_url ?? '') }}"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    class="w-full bg-gray-950 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none placeholder-gray-600">
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Replaces the cover image on the product page when set.</p>
                        </div>
                    </div>

                    {{-- Screenshots / Album --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Screenshots / Album</label>

                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3" id="existing-screenshots">
                            @if(isset($addon) && $addon->screenshots && count($addon->screenshots) > 0)
                                @foreach($addon->screenshots as $index => $screenshot)
                                <div class="relative group" data-screenshot-item data-path="{{ $screenshot }}">
                                    <img src="{{ asset('storage/' . $screenshot) }}" alt="Screenshot" class="w-full h-24 object-cover rounded-lg border border-white/10">
                                    <button type="button" onclick="screenshotRemoveExisting(this)"
                                        class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all shadow-lg">&times;</button>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3 hidden" id="new-screenshots-previews"></div>

                        <div id="screenshots-dropzone"
                            class="relative border-2 border-dashed border-white/10 hover:border-purple-500/40 hover:bg-purple-500/5 rounded-xl p-6 text-center cursor-pointer transition-all group">
                            <input type="file" name="screenshots[]" accept="image/*" multiple id="screenshots-file-input" class="hidden">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                                    <i class="fa-solid fa-cloud-arrow-up text-purple-400"></i>
                                </div>
                                <p class="text-sm text-gray-300">Drop screenshots or <span class="text-purple-400 font-medium">browse</span></p>
                                <p class="text-[11px] text-gray-500">PNG · JPG · WEBP — up to 5MB each</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500" id="screenshots-count">0 new image(s) selected</p>
                            <button type="button" id="screenshots-clear-btn" onclick="screenshotsClearAll()" class="text-xs text-red-400 hover:text-red-300 transition-colors hidden">Clear all new</button>
                        </div>

                        <input type="hidden" name="removed_screenshots" id="removed_screenshots" value="[]">
                    </div>
                </div>
            </section>

            {{-- Features --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/15 flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-amber-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Feature List</h2>
                        <p class="text-xs text-gray-500">Bullet points shown on the product sidebar. JSON array format.</p>
                    </div>
                </header>

                <textarea name="features" rows="4"
                    placeholder='["Feature 1", "Feature 2", "Feature 3"]'
                    class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:outline-none resize-none font-mono text-xs placeholder-gray-600">{{ old('features', isset($addon) ? json_encode($addon->features) : '') }}</textarea>
            </section>

            {{-- Download --}}
            <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                <header class="mb-5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/15 flex items-center justify-center">
                        <i class="fa-solid fa-download text-teal-300 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold">Downloadable File</h2>
                        <p class="text-xs text-gray-500">Upload a file or paste an external URL — uploaded file wins if both are set.</p>
                    </div>
                </header>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Upload File</label>
                        <input type="file" name="file"
                            class="w-full bg-gray-950 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none file:bg-purple-600 file:hover:bg-purple-500 file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3 file:cursor-pointer file:text-xs">

                        @if(isset($addon) && $addon->file_path)
                        <div class="mt-3 flex items-center justify-between gap-3 p-3 rounded-lg bg-white/5 border border-white/5">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-teal-500/10 flex items-center justify-center shrink-0">
                                    <i class="fa-solid {{ str_starts_with($addon->file_path, 'http') ? 'fa-link' : 'fa-file-zipper' }} text-teal-300 text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm text-white truncate">{{ str_starts_with($addon->file_path, 'http') ? 'External URL' : basename($addon->file_path) }}</div>
                                    <div class="text-[11px] text-gray-500">Current file</div>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-1.5 text-xs text-red-400 hover:text-red-300 cursor-pointer transition-colors shrink-0">
                                <input type="checkbox" name="remove_file" value="1" class="w-3.5 h-3.5 rounded bg-white/5 border-white/10 text-red-600 focus:ring-red-500">
                                Remove
                            </label>
                        </div>
                        @endif
                    </div>

                    <div class="relative flex items-center gap-3 my-3">
                        <div class="flex-1 border-t border-white/5"></div>
                        <span class="text-[10px] uppercase tracking-wider text-gray-600 font-medium">or</span>
                        <div class="flex-1 border-t border-white/5"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">External URL</label>
                        <div class="relative">
                            <i class="fa-solid fa-link absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none"></i>
                            <input type="url" name="download_url"
                                value="{{ old('download_url', (isset($addon) && $addon->file_path && str_starts_with($addon->file_path, 'http')) ? $addon->file_path : '') }}"
                                placeholder="https://drive.google.com/uc?export=download&id=..."
                                class="w-full bg-gray-950 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white text-sm focus:border-purple-500 focus:outline-none placeholder-gray-600">
                        </div>
                        <p class="text-[11px] text-gray-600 mt-1.5">Works with Google Drive, Dropbox, GitHub, or any direct download URL.</p>
                    </div>
                </div>
            </section>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <aside class="space-y-6">
            <div class="xl:sticky xl:top-24 space-y-6">

                {{-- Save card --}}
                <section class="rounded-2xl bg-gradient-to-br from-purple-600/15 via-violet-700/10 to-gray-900 border border-purple-500/20 p-6">
                    <h3 class="font-semibold mb-1">{{ isset($addon) ? 'Update Add-on' : 'Publish Add-on' }}</h3>
                    <p class="text-xs text-gray-400 mb-4">All fields with * are required.</p>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 rounded-xl transition-all hover:shadow-xl hover:shadow-purple-500/30">
                        <i class="fa-solid fa-{{ isset($addon) ? 'floppy-disk' : 'rocket' }}"></i>
                        {{ isset($addon) ? 'Save Changes' : 'Create Add-on' }}
                    </button>
                    <a href="{{ route('admin.addons.index') }}"
                        class="block w-full text-center mt-2 bg-white/5 hover:bg-white/10 text-gray-300 font-medium py-3 rounded-xl transition-all">
                        Cancel
                    </a>
                </section>

                {{-- Visibility --}}
                <section class="rounded-2xl bg-gray-900 border border-white/5 p-6">
                    <header class="mb-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-500/15 flex items-center justify-center">
                            <i class="fa-solid fa-eye text-amber-300 text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Visibility</h3>
                            <p class="text-xs text-gray-500">Promote on the home page.</p>
                        </div>
                    </header>

                    <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 cursor-pointer transition-colors">
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $addon->is_featured ?? false) ? 'checked' : '' }}
                            class="w-5 h-5 rounded bg-gray-950 border-white/10 text-purple-600 focus:ring-purple-500">
                        <div class="min-w-0">
                            <div class="text-sm font-medium">Featured</div>
                            <div class="text-[11px] text-gray-500">Show on the featured section on home.</div>
                        </div>
                        <i class="fa-solid fa-star text-amber-400 ml-auto"></i>
                    </label>
                </section>

                {{-- Tips --}}
                <section class="rounded-2xl bg-gray-900 border border-white/5 p-6 text-xs text-gray-400 space-y-3">
                    <div class="flex items-center gap-2 text-gray-300 font-semibold text-sm">
                        <i class="fa-solid fa-lightbulb text-amber-400"></i> Quick tips
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>Set price to <strong class="text-gray-200">$0</strong> for a free download.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>Define <strong class="text-gray-200">one pack</strong> for a single-offer flow, or multiple for tiered packs.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-400 mt-0.5"></i>
                        <span>YouTube URL replaces the cover image on the product page.</span>
                    </div>
                </section>
            </div>
        </aside>
    </div>
</form>

{{-- License Packs Builder --}}
<script>
(function() {
    var existing = @json(old('license_tiers_raw', isset($addon) ? ($addon->license_tiers ? json_encode($addon->license_tiers) : '[]') : '[]'));
    var tiers = [];
    try { tiers = JSON.parse(existing); } catch(e) { tiers = []; }

    var list = document.getElementById('license-tiers-list');
    var input = document.getElementById('license_tiers_input');
    var addBtn = document.getElementById('add-tier-btn');
    if (!list || !addBtn) return;

    function syncInput() {
        input.value = JSON.stringify(tiers);
    }

    function escHtml(str) {
        return (str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderTiers() {
        list.innerHTML = '';
        if (tiers.length === 0) {
            list.innerHTML =
                '<div class="rounded-xl border-2 border-dashed border-white/10 p-8 text-center">' +
                    '<div class="w-12 h-12 mx-auto rounded-2xl bg-white/5 flex items-center justify-center mb-3">' +
                        '<i class="fa-solid fa-box-open text-gray-600"></i>' +
                    '</div>' +
                    '<p class="text-sm text-gray-400">No packs defined yet.</p>' +
                    '<p class="text-xs text-gray-600 mt-1">Click <strong class="text-gray-400">+ Add Pack</strong> above to create one.</p>' +
                '</div>';
            syncInput();
            return;
        }

        tiers.forEach(function(tier, idx) {
            var qty = parseInt(tier.quantity) || 1;
            var price = parseFloat(tier.price) || 0;
            var perLic = qty > 0 ? (price / qty) : 0;
            var isSingle = tiers.length === 1;

            var row = document.createElement('div');
            row.className = 'group relative rounded-2xl bg-gradient-to-br from-white/[0.04] to-white/[0.01] border border-white/10 p-5 hover:border-purple-500/30 transition-all';
            row.innerHTML =
                '<div class="absolute -top-2.5 left-5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-600 text-white text-[10px] font-semibold uppercase tracking-wider shadow-lg shadow-purple-500/30">' +
                    '<i class="fa-solid fa-layer-group text-[9px]"></i> Pack #' + (idx + 1) +
                    (isSingle ? '<span class="ml-1 opacity-80">· auto-applied</span>' : '') +
                '</div>' +
                '<div class="grid grid-cols-1 md:grid-cols-[1fr_120px_160px_auto] gap-3 items-end mt-2">' +
                    '<div>' +
                        '<label class="block text-[10px] font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Label <span class="normal-case text-gray-600">(optional)</span></label>' +
                        '<input type="text" placeholder="e.g. Personal, Studio…" value="' + escHtml(tier.label) + '" data-idx="' + idx + '" data-field="label" ' +
                            'class="w-full bg-gray-950 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none placeholder-gray-600">' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-[10px] font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Licenses</label>' +
                        '<div class="relative">' +
                            '<i class="fa-solid fa-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs pointer-events-none"></i>' +
                            '<input type="number" min="1" placeholder="1" value="' + qty + '" data-idx="' + idx + '" data-field="quantity" ' +
                                'class="w-full bg-gray-950 border border-white/10 rounded-lg pl-8 pr-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none text-center">' +
                        '</div>' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-[10px] font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Price</label>' +
                        '<div class="relative">' +
                            '<span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none">$</span>' +
                            '<input type="number" step="0.01" min="0" placeholder="0.00" value="' + (tier.price !== undefined ? tier.price : '') + '" data-idx="' + idx + '" data-field="price" ' +
                                'class="w-full bg-gray-950 border border-white/10 rounded-lg pl-7 pr-3 py-2 text-sm text-white focus:border-purple-500 focus:outline-none">' +
                        '</div>' +
                    '</div>' +
                    '<button type="button" data-remove="' + idx + '" title="Remove pack" ' +
                        'class="w-10 h-10 rounded-lg bg-red-500/10 hover:bg-red-500/30 text-red-400 flex items-center justify-center text-sm transition-colors self-end">' +
                        '<i class="fa-solid fa-trash-can text-xs"></i>' +
                    '</button>' +
                '</div>' +
                '<div class="mt-3 flex items-center justify-between gap-3 text-[11px] text-gray-500 px-1">' +
                    '<span><i class="fa-solid fa-calculator mr-1 opacity-60"></i> ' +
                        (qty > 0 && price > 0 ? '$' + perLic.toFixed(2) + ' per license' : '—') +
                    '</span>' +
                    (price > 0 ? '<span class="text-emerald-400/80">Total $' + price.toFixed(2) + '</span>' : '<span></span>') +
                '</div>';
            list.appendChild(row);
        });

        list.querySelectorAll('input[data-field]').forEach(function(inp) {
            inp.addEventListener('input', function() {
                var i = parseInt(this.dataset.idx);
                var field = this.dataset.field;
                if (field === 'price') {
                    tiers[i][field] = parseFloat(this.value) || 0;
                } else if (field === 'quantity') {
                    tiers[i][field] = Math.max(1, parseInt(this.value) || 1);
                } else {
                    tiers[i][field] = this.value;
                }
                syncInput();
            });
            inp.addEventListener('change', function() {
                if (this.dataset.field === 'price' || this.dataset.field === 'quantity') {
                    renderTiers();
                }
            });
        });

        list.querySelectorAll('[data-remove]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                tiers.splice(parseInt(this.dataset.remove), 1);
                renderTiers();
            });
        });

        syncInput();
    }

    addBtn.addEventListener('click', function() {
        tiers.push({ label: '', quantity: 1, price: 0 });
        renderTiers();
        var inputs = list.querySelectorAll('input[data-field="label"]');
        if (inputs.length) inputs[inputs.length - 1].focus();
    });

    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() { syncInput(); });
    }

    renderTiers();
})();
</script>

{{-- Screenshots Uploader --}}
<script>
(function() {
    const dropzone = document.getElementById('screenshots-dropzone');
    const fileInput = document.getElementById('screenshots-file-input');
    const previewsContainer = document.getElementById('new-screenshots-previews');
    const countEl = document.getElementById('screenshots-count');
    const clearBtn = document.getElementById('screenshots-clear-btn');
    let files = [];

    dropzone.addEventListener('click', function() { fileInput.click(); });

    fileInput.addEventListener('change', function() { addFiles(this.files); });

    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('border-purple-500', 'bg-purple-500/10');
        dropzone.classList.remove('border-white/10');
    });
    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-purple-500', 'bg-purple-500/10');
        dropzone.classList.add('border-white/10');
    });
    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-purple-500', 'bg-purple-500/10');
        dropzone.classList.add('border-white/10');
        addFiles(e.dataTransfer.files);
    });

    function addFiles(newFiles) {
        for (const file of newFiles) {
            if (!file.type.startsWith('image/')) continue;
            if (file.size > 5 * 1024 * 1024) continue;
            files.push(file);
        }
        renderPreviews();
        syncInput();
    }

    function renderPreviews() {
        previewsContainer.innerHTML = '';
        if (files.length === 0) {
            previewsContainer.classList.add('hidden');
            clearBtn.classList.add('hidden');
        } else {
            previewsContainer.classList.remove('hidden');
            clearBtn.classList.remove('hidden');
        }
        countEl.textContent = files.length + ' new image(s) selected';

        files.forEach(function(file, index) {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML =
                '<img src="' + url + '" alt="New" class="w-full h-24 object-cover rounded-lg border border-purple-500/30">' +
                '<button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-600/90 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-all shadow-lg">&times;</button>' +
                '<div class="absolute bottom-1 left-1 right-1 text-[10px] text-white/70 truncate px-1">' + file.name.replace(/</g, '&lt;') + '</div>';
            div.querySelector('button').addEventListener('click', function() {
                URL.revokeObjectURL(url);
                files.splice(index, 1);
                renderPreviews();
                syncInput();
            });
            previewsContainer.appendChild(div);
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(function(f) { dt.items.add(f); });
        fileInput.files = dt.files;
    }

    window.screenshotsClearAll = function() {
        files = [];
        renderPreviews();
        syncInput();
    };

    window.screenshotRemoveExisting = function(btn) {
        const item = btn.closest('[data-screenshot-item]');
        const path = item.getAttribute('data-path');
        const hidden = document.getElementById('removed_screenshots');
        const removed = JSON.parse(hidden.value);
        removed.push(path);
        hidden.value = JSON.stringify(removed);
        item.remove();
    };
})();
</script>

<script src="https://cdn.tiny.cloud/1/2ybotr2gj2jba7rs525xlvymht3kg2qv4833vglziifs7kj8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#addon-description',
        skin: 'oxide-dark',
        content_css: 'dark',
        menubar: false,
        plugins: 'lists link image code table',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image table | code',
        height: 300,
        branding: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; color: #e5e7eb; background: #111827; }',
    });

    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            tinymce.triggerSave();
        });
    }
});
</script>
@endsection
