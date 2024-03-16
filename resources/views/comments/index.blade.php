<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Comment</title>
</head>
<body>
    <form action="{{ route('comments.store') }}" method="POST">
    <div class="row">
        @csrf
        <div class="col-12">
            <div class="form-group">
                <label for="comment">Comment</label>
                <input type="hidden" name="news_id" value="1">
                <textarea class="form-control" name="comment_body" id="comment" cols="30" rows="10" placeholder="Enter your comment"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    </form>
</body>
</html>