<table border="0" style="background: none; color: <?php echo $style['color']; ?>; border: 0; border-collapse: separate; border-spacing: <?php echo $style['spacing']; ?>px; margin: 0; padding: 0; width: 100%; table-layout: fixed; font: normal normal <?php echo $style['font']['size']; ?>px/1.5em <?php echo $style['font']['face']; ?>; text-align: <?php echo $style['text']['align']; ?>;">
  <tbody style="background: inherit; color: inherit; border: 0; margin: 0; padding: 0; font: inherit; text-align: inherit;">
    <?php $back = (array) $style['background']; $back_alt = isset($back[1]) ? $back[1] : $back[0]; ?>
    <?php foreach ($data as $k => $v): ?>
    <?php if (!$v) continue; ?>
    <tr style="background: inherit; color: inherit; border: 0; margin: 0; padding: 0; font: inherit; text-align: inherit;">
      <th style="background: <?php echo $back_alt; ?>; color: inherit; border: 0; margin: 0; padding: .5em .8em; font: inherit; font-weight: bold; text-align: inherit; vertical-align: top; width: 25%;"><?php echo $language->{$k}; ?></th>
      <td style="background: <?php echo $back[0]; ?>; color: inherit; border: 0; margin: 0; padding: .5em .8em; font: inherit; text-align: inherit; vertical-align: top;"><?php echo $v; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>