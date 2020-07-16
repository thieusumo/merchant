<div id="canvas_text_editor" class="border">
    <div id="text-controls" class="form-inline">
        <input type="button" value="Add" class="btn btn-addtext" id="btnAddText" style="width:38px;"/>
        <input type="button" value="Delete" class="btn btn-deletetext" id="btnDeleteText" style="width:38px;"/>
        <input type="color" value="" id="text-color" class="form-control" title="Color">        
        <select id="font-family" class="form-control" style="width: auto;">
            <option value="arial">Arial</option>
            <option value="helvetica" selected>Helvetica</option>
            <option value="myriad pro">Myriad Pro</option>
            <option value="delicious">Delicious</option>
            <option value="verdana">Verdana</option>
            <option value="georgia">Georgia</option>
            <option value="courier">Courier</option>
            <option value="comic sans ms">Comic Sans MS</option>
            <option value="impact">Impact</option>
            <option value="monaco">Monaco</option>
            <option value="optima">Optima</option>
            <option value="hoefler text">Hoefler Text</option>
            <option value="plaster">Plaster</option>
            <option value="engagement">Engagement</option>
        </select>
<!--        
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size" aria-expanded="false"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>            
            <ul class="dropdown-menu border-0" style="min-width:200px;">
              <li><input type="range" value="" class="custom-range" min="10" max="80" step="1" id="text-font-size"></li>              
            </ul>
          </div>
-->
        <div  id="text-controls-additional" class="btn-group">
            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
            <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
            <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
        </div>
    </div>
    
</div>    