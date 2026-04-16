<div class="kit-field">
    <label for="job_category_id">Category</label>
    <select id="job_category_id" name="job_category_id">
        <option value="">Select category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('job_category_id', $job?->job_category_id) == $category->id)>{{ $category->category_name }}</option>
        @endforeach
    </select>
</div>
<div class="kit-field"><label for="title">Title</label><input id="title" type="text" name="title" value="{{ old('title', $job?->title) }}" required></div>
<div class="kit-field" style="flex-basis: 100%;">
    <label for="description_editor">Description</label>
    <input id="description" type="hidden" name="description" value="{{ old('description', $job?->description) }}">
    <trix-editor input="description" id="description_editor" data-portal-editor class="portal-trix-editor"></trix-editor>
</div>
<div class="kit-field" style="flex-basis: 100%;"><label for="requirements">Requirements</label><textarea id="requirements" name="requirements" rows="4">{{ old('requirements', $job?->requirements) }}</textarea></div>
<div class="kit-field" style="flex-basis: 100%;"><label for="responsibilities">Responsibilities</label><textarea id="responsibilities" name="responsibilities" rows="4">{{ old('responsibilities', $job?->responsibilities) }}</textarea></div>
<div class="kit-field" style="flex-basis: 100%;"><label for="qualifications">Qualifications</label><textarea id="qualifications" name="qualifications" rows="4">{{ old('qualifications', $job?->qualifications) }}</textarea></div>
<div class="kit-field"><label for="salary_min">Minimum Salary</label><input id="salary_min" type="number" name="salary_min" value="{{ old('salary_min', $job?->salary_min) }}"></div>
<div class="kit-field"><label for="salary_max">Maximum Salary</label><input id="salary_max" type="number" name="salary_max" value="{{ old('salary_max', $job?->salary_max) }}"></div>
<div class="kit-field"><label for="salary_type">Salary Type</label><select id="salary_type" name="salary_type">@foreach (['monthly', 'yearly', 'hourly', 'project'] as $type)<option value="{{ $type }}" @selected(old('salary_type', $job?->salary_type ?? 'monthly') === $type)>{{ ucfirst($type) }}</option>@endforeach</select></div>
<div class="kit-field"><label for="location">Location</label><input id="location" type="text" name="location" value="{{ old('location', $job?->location) }}"></div>
<div class="kit-field"><label for="job_type">Job Type</label><select id="job_type" name="job_type">@foreach (['full-time', 'part-time', 'contract', 'freelance', 'internship'] as $type)<option value="{{ $type }}" @selected(old('job_type', $job?->job_type ?? 'full-time') === $type)>{{ ucfirst($type) }}</option>@endforeach</select></div>
<div class="kit-field"><label for="experience_level">Experience</label><select id="experience_level" name="experience_level">@foreach (['entry', 'junior', 'senior', 'lead'] as $level)<option value="{{ $level }}" @selected(old('experience_level', $job?->experience_level ?? 'entry') === $level)>{{ ucfirst($level) }}</option>@endforeach</select></div>
<div class="kit-field"><label for="education_requirement">Education Requirement</label><input id="education_requirement" type="text" name="education_requirement" value="{{ old('education_requirement', $job?->education_requirement) }}"></div>
<div class="kit-field"><label for="skills_required">Skills Required (comma separated)</label><input id="skills_required" type="text" name="skills_required[]" value="{{ implode(', ', $job?->skills_required ?? []) }}"></div>
<div class="kit-field"><label for="application_deadline">Deadline</label><input id="application_deadline" type="date" name="application_deadline" value="{{ old('application_deadline', optional($job?->application_deadline)->format('Y-m-d')) }}"></div>
<div class="kit-field"><label for="max_applicants">Maximum Applicants</label><input id="max_applicants" type="number" name="max_applicants" value="{{ old('max_applicants', $job?->max_applicants) }}"></div>
<label class="portal-inline-check"><input type="checkbox" name="is_remote" value="1" @checked(old('is_remote', $job?->is_remote))><span>Remote position</span></label>
<label class="portal-inline-check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $job?->is_featured))><span>Feature this job</span></label>
