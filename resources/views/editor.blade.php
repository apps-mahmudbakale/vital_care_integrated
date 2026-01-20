
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
    integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
  <link href="{{asset('style.css')}}" rel="stylesheet">

<body onload="init()">
  <div id="editor">
    <div id="toolbar">
      <div id="toolbar-section1" class="toolbar-section">
        <select id="fontName" class="format-select">
          <option disabled>Font</option>
          <option id="arial">Arial</option>
          <option id="courier">Courier New</option>
          <option id="georgia">Georgia</option>
          <option id="helvetica">Helvetica</option>
          <option id="tahoma">Tahoma</option>
          <option id="times" selected>Times New Roman</option>
          <option id="trebuchet">Trebuchet MS</option>
          <option id="verdana">Verdana</option>
        </select>
        <select id="fontSize" class="format-select">
          <option disabled>Size</option>
          <option value="1">10</option>
          <option value="2">13</option>
          <option value="3" selected>16</option>
          <option value="4">18</option>
          <option value="5">24</option>
          <option value="6">32</option>
          <option value="7">48</option>
        </select>
        <select id="foreColor" class="format-select">
          <option disabled>Colour</option>
          <option value="black" style="background:black; color:black" selected>Black</option>
          <option value="#fc1303" style="background:#fc1303; color:#fc1303">Red</option>
          <option value="#fc9d03" style="background:#fc9d03; color:#fc9d03">Orange</option>
          <option value="#fcd703" style="background:#fcd703; color:#fcd703">Yellow</option>
          <option value="#4ec244" style="background:#4ec244; color:#4ec244">Green</option>
          <option value="#44c29c" style="background:#68f7cc; color:#68f7cc">Turquoise</option>
          <option value="#51c3f0" style="background:#51c3f0; color:#51c3f0">Blue</option>
          <option value="#4b63cc" style="background:#4b63cc; color:#4b63cc">Dark blue</option>
          <option value="#9c44d4" style="background:#9c44d4; color:#9c44d4">Purple</option>
          <option value="#d4449f" style="background:#d4449f; color:#d4449f">Pink</option>
        </select>
      </div>
      <div id="toolbar-section2" class="toolbar-section">
        <!-- Formatting UI didn't work as icons -->
        <button id="bold" class="fas fa-bold icon toggle"></button>
        <button id="italic" class="fas fa-italic icon toggle"></button>
        <button id="underline" class="fas fa-underline icon toggle"></button>
      </div>
      <div id="toolbar-section3" class="toolbar-section">
        <button id="insertUnorderedList" class="fas fa-list-ul icon"></button>
        <button id="insertOrderedList" class="fas fa-list-ol icon"></button>
      </div> 
      <div id="toolbar-section4" class="toolbar-section">
        <button id="justifyLeft" class="fas fa-align-left icon toggle"></button>
        <button id="justifyCenter" class="fas fa-align-center icon toggle"></button>
        <button id="justifyRight" class="fas fa-align-right icon toggle"></button>
      </div> 
      <div id="toolbar-section5" class="toolbar-section">
        <button id="subscript" class="fas fa-subscript icon toggle"></button>
        <button id="superscript" class="fas fa-superscript icon toggle"></button>
        <select id="mathChars" class="format-select">
          <option selected disabled>Math</option>
          <option value="−">&minus;</option>
          <option value="+">&plus;</option>
          <option value="×">&times;</option>
          <option value="÷">&divide;</option>
          <option value="≤">&le;</option>
          <option value="≥">&ge;</option>
          <option value="≠">&ne;</option>
          <option value="√">&radic;</option>
          <option value="∞">&infin;</option>
          <option value="∫">&int;</option>
          <option value="∑">&sum;</option>
          <option value="∏">&prod;</option>
          <option value="α">&alpha;</option>
          <option value="β">&beta;</option>
          <option value="Π">&pi;</option>
          <option value="θ">&theta;</option>
          <option value="⇐">&lArr;</option>
          <option value="⇑">&uArr;</option>
          <option value="⇒">&rArr;</option>
          <option value="⇓">&dArr;</option>
          <option value="⇔">&hArr;</option>
          <option value="∠">&ang;</option>
          <option value="∴">&there4;</option>
        </select>
        <button id="createLink" class="fas fa-link icon"></button>
      </div>
    </div>
    <div id="textbox" contenteditable="true"></div>
  </div>
  <script src="{{asset('script.js')}}"></script>
</body>
</html>