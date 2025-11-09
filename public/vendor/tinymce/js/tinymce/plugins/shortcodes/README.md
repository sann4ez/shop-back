# TinyMCE Custom Plugins

<details>
  <summary><strong>Shortcodes</strong></summary>
  
  #### Опис

  > Плагін <strong>shortcodes</strong> для TinyMCE дозволяє користувачам легко вставляти шорткоди в редактор тексту через зручний інтерфейс. Він підтримує як статичні, так і динамічні шорткоди. 

  #### Основні функції

  > - **Меню шорткодів**: Дозволяє користувачам вибирати шорткоди з контекстного меню.
  > - **Копіювання та вставка шорткодів**: Залежно від режиму, якщо встановлено `'shortcodes-mode' => 'paste'`, шорткод одразу вставляється у поле вводу, де знаходиться курсор. Якщо режим `'shortcodes-mode' => 'clipboard'`, то шорткод копіюється для подальшого використання. За замовчуванням активовано режим копіювання та вставки — `'shortcodes-mode' => 'clipboard-paste'`.
  > - **Сповіщення**: Інформує користувачів про успішне копіювання шорткоду або про помилки.

  #### Встановлення

  > 1. Завантажити файл `shortcodes.min.js` та перейменувати його на `plugin.min.js`.
  > 2. В директорії TinyMCE знайти папку `plugins` та створити в ній папку `shortcodes`, перемістити туди файл, який був завантажений та перейменований.
  > 3. У файлі, де ініціалізується TinyMCE, додати `shortcodes` до параметрів `plugins` та `toolbar`:
  ```javascript
     tinymce.init({
         plugins: 'anchor code shortcodes',
         toolbar: 'fullscreen | shortcodes',
     });
  ```
  
  Готово!

  #### Приклади використання

  > 1. **Статичні шорткоди**:
   ```html
      <textarea class="f-tinymce" data-shortcodes-mode="clipboard-paste" data-shortcodes='@json([["key" => "[user:email]", "name" => "Email користувача"]])'></textarea>
   ```
   ```html
      <textarea class="f-tinymce" data-shortcodes-mode="clipboard" data-shortcodes='{{ json_encode([["key" => "[user:firstname]", "name" => "Ім'я користувача"], ["key" => "[user:lastname]", "name" => "Прізвище користувача"]]) }}'></textarea>
   ```

  > 2. **Динамічні шорткоди**:
   ```html
     <textarea class="f-tinymce" data-shortcodes-mode="paste" data-shortcodes-url="http://site.test/api/shortcodes"></textarea>
   ```
</details>
