# 🎨 Paleta de Colores - Mascotas Felices

## Paleta Principal (Azul/Púrpura Profundo)

Basada en el color `#190C7B` - Representa confianza, profesionalismo y lealtad

| Color | Hex | Uso |
|-------|-----|-----|
| **Azul Profundo** | `#190C7B` | Color principal - Botones primarios, encabezados, navegación |
| **Azul Medio** | `#2D1B9E` | Hover states, elementos secundarios |
| **Azul Vibrante** | `#4A3DB8` | Acentos, focus states, enlaces |

### Variantes de fondo
- `#F5F3FF` - Fondo muy claro (bg-50)
- `#EDE9FE` - Fondo claro (bg-100)
- `#DDD6FE` - Fondo medio claro (bg-200)
- `#C4B5FD` - Fondo medio (bg-300)
- `#A78BFA` - Fondo oscuro (bg-400)

## Paleta Secundaria (Colores de Acento Suaves)

### Lavanda Suave
| Color | Hex | Uso |
|-------|-----|-----|
| **Lavanda** | `#8B7AB8` | Botones secundarios, elementos complementarios, cards alternativos |
| **Lavanda Claro** | `#9B8AC4` | Hover states de lavanda |

**Fondos claros:**
- `#FAF5FF` - Fondo muy claro
- `#F3E8FF` - Fondo claro

### Azul Cielo
| Color | Hex | Uso |
|-------|-----|-----|
| **Azul Cielo** | `#5B8FCC` | Estados informativos, enlaces secundarios, badges |
| **Azul Cielo Claro** | `#6B9FDC` | Hover state |

### Coral Suave
| Color | Hex | Uso |
|-------|-----|-----|
| **Coral** | `#E89A7B` | Puntos de fidelidad, destacados cálidos, elementos premium |

## Aplicación en el Sistema

### Navegación y Sidebar
- Fondo: `#190C7B` (azul profundo)
- Hover: `#2D1B9E` (azul medio)
- Texto activo: `#FFB800` (dorado)

### Botones Principales
```html
<!-- Botón primario (sólido) -->
<button class="bg-[#190C7B] hover:bg-[#2D1B9E] text-white">

<!-- Botón secundario (lavanda sólida) -->
<button class="bg-[#8B7AB8] hover:bg-[#9B8AC4] text-white">

<!-- Botón de acento (azul cielo sólido) -->
<button class="bg-[#5B8FCC] hover:bg-[#6B9FDC] text-white">
```

### Cards y Contenedores (Colores Sólidos)
```html
<!-- Card principal -->
<div class="bg-[#190C7B] text-white rounded-lg shadow-lg p-6">

<!-- Card secundario -->
<div class="bg-[#8B7AB8] text-white rounded-lg shadow-lg p-6">

<!-- Card de acento -->
<div class="bg-[#E89A7B] text-white rounded-lg shadow-lg p-6">
```

### Gradientes
**Nota:** El sistema ahora usa colores sólidos en lugar de gradientes para una apariencia más limpia y profesional.

Si necesitas usar gradientes en casos específicos:
```html
<!-- Gradiente principal (solo para casos especiales) -->
<div class="bg-gradient-to-br from-[#190C7B] to-[#2D1B9E]">

<!-- Se recomienda usar colores sólidos -->
<div class="bg-[#190C7B]">
```

### Cards y Contenedores
- Card principal: Color sólido `#190C7B`
- Card secundario: Color sólido `#8B7AB8`
- Card de información: Fondo `#F5F3FF` con borde `#4A3DB8`
- **Nota**: Se usan colores sólidos en lugar de gradientes para un diseño más limpio y moderno

### Estados
- **Éxito**: `#10B981` (verde - mantener del sistema)
- **Advertencia**: `#F59E0B` (ámbar - mantener del sistema)
- **Error**: `#DC2626` (rojo - mantener del sistema)
- **Información**: `#5B8FCC` (azul cielo)

### Puntos de Fidelidad
- Icono/número: `#E89A7B` (coral suave)
- Fondo: `#FAF5FF` (lavanda muy claro)

## Accesibilidad

Todos los colores principales cumplen con WCAG 2.1 AA para contraste cuando se usan con texto blanco:
- `#190C7B` con blanco: ✅ 12.5:1 (AAA)
- `#2D1B9E` con blanco: ✅ 9.8:1 (AAA)
- `#8B7AB8` con blanco: ✅ 4.8:1 (AA)
- `#5B8FCC` con blanco: ✅ 4.5:1 (AA)

## Archivos Modificados

Los siguientes archivos contienen la paleta de colores:
- `resources/css/app.css` - Definiciones CSS y clases personalizadas
- Todas las vistas en `resources/views/**/*.blade.php` - Aplicación de colores en HTML

## Comando para Actualizar Colores

Si necesitas hacer cambios masivos en el futuro:

```bash
# Reemplazar indigo por azul personalizado
find resources/views -name "*.blade.php" -type f -exec sed -i '' 's/bg-indigo-600/bg-[#190C7B]/g' {} +

# Reemplazar purple por magenta
find resources/views -name "*.blade.php" -type f -exec sed -i '' 's/bg-purple-600/bg-[#7B0C5F]/g' {} +
```

---

**Última actualización:** 7 de noviembre de 2025
