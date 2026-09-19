<form action="/newsletter/subscribe" method="POST" class="flex flex-col sm:flex-row gap-2">
  @csrf
  <label for="newsletter-email-{{ uniqid() }}" class="sr-only">Alamat email</label>
  <input type="email" name="email" required placeholder="Alamat email" class="flex-1 border rounded-lg px-3 py-2 text-sm min-h-[44px]">
  <button type="submit" class="px-4 py-2 bg-blue-700 text-white text-sm rounded-lg min-h-[44px]">Langganan</button>
</form>
