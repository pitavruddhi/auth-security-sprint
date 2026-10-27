
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category | Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Category</h2>

                <a href="{{ route('admin.categories.index') }}"
                   class="btn btn-outline-secondary">
                    Back
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ route('admin.categories.update', $category) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                Slug (optional)
                            </label>

                            <input type="text"
                                   name="slug"
                                   id="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $category->slug) }}">

                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parent_id" class="form-label">
                                Parent Category
                            </label>

                            <select name="parent_id"
                                    id="parent_id"
                                    class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">None (Top-level category)</option>

                                @foreach ($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}"
                                        @selected(old('parent_id', $category->parent_id) == $parentCategory->id)>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Category
                        </button>

                        <a href="{{ route('admin.categories.index') }}"
                           class="btn btn-light">
                            Cancel
                        </a>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
