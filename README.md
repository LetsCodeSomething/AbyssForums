# AbyssForums - итоговый проект
<p>Этот проект является простым форумом.</p> 
<p>В проекте реализована система аутентификации и регистрации. Аутентифицированные пользователи могут просматривать свой профиль, создавать посты, редактировать или удалять свои посты, а также оставлять и удалять комментарии. При желании можно удалить свой профиль и все связанные с ним данные.</p>

## Модель данных
<p>В модели присутствуют пять сущностей: пользователь, достижение, достижение_пользователя, пост и комментарий.</p>
<p>Связи в модели:</p>
<ul>
  <li>Пользователь - Достижение: N:M (многие ко многим)</li>
  <li>Пользователь - Пост: 1:N (один ко многим)</li>
  <li>Пост - Комментарий: 1:N (один ко многим)</li>
</ul>
<p>При удалении поста удаляются все комментарии к нему. При удалении профиля пользователя удаляются все его посты и комментарии.</p>
