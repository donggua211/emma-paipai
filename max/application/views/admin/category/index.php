<h2 class="title">商品分类</h2>

<div class="main-content">
	<div class="detail-list">
		<table border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
				  <td>分类名称</td>
				  <td class="operation">操作</td>
				</tr>
			</thead>
			<tbody>
				<?php 
				$display_field = array('category_name');
				$option_links = array(
					array('text' => '编辑', 'uri' => 'admin/category/edit'), 
					array('text' => '删除', 'uri' => 'admin/category/remove', 'class' => 'remove')
				);
				template_tree_list($category_list, $display_field, $option_links); ?>
			</tbody>
		</table>
	</div>
</div>