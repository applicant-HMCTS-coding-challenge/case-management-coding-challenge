<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
				
		<link rel="preload" href="/fonts/nunito-v31-latin-700.woff2" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="/fonts/nunito-v31-latin-regular.woff2" as="font" type="font/woff2" crossorigin>

		@vite('resources/css/app.css')

		<!-- Meta tags -->
		<title>Caseworkers Tasks Portal</title>
		<meta name="description" content="Create and Manage tasks for Case workers">
	</head>
	<body>
		<div id="root"></div>
        
        @vite('resources/js/app.jsx')
	</body>
</html>