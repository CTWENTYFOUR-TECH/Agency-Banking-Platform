function limitInputLength(el) {
    if (el.value.length > 11) {
      el.value = el.value.slice(0, 11);
    }
  }