$(document).ready(function () {
  // Inicialize o Select2 em todos os elementos .select2 dentro de modais quando o modal for mostrado
  $(".modal").on("shown.bs.modal", function () {
    var $modal = $(this);
    $modal.find(".select2").each(function () {
      $(this)
        .select2({
          dropdownParent: $modal.find(".modal-content"),
          language: {
            noResults: function () {
              return "Nenhum resultado encontrado";
            },
          },
        })
        .on("select2:open", function () {
          var select2Instance = $(this).data("select2");
          if (select2Instance) {
            select2Instance.dropdown._positionDropdown();
          }
        });
    });
  });

  // Inicialize o Select2 em todos os elementos .select2 fora de modais
  $(".select2").each(function () {
    $(this).select2({
      language: {
        noResults: function () {
          return "Nenhum resultado encontrado";
        },
      },
    });
  });

  // Attach event handler for window resize
  $(window).on("resize", function () {
    // Reposicionar os dropdowns Select2 abertos
    $(".select2").each(function () {
      var select2Instance = $(this).data("select2");
      if (select2Instance) {
        select2Instance.dropdown._positionDropdown();
      }
    });
  });
});

// Inicializa o DataTables
function initDataTable(tableSelector) {
  var table = $("#" + tableSelector).DataTable({
    responsive: true,
    lengthChange: true,
    pageLength: 5,
    stateSave: true,
    autoWidth: false,
    lengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, "Todos"],
    ],
    buttons: ["excel", "colvis"],
    language: {
      emptyTable: "Nenhum registro encontrado",
      info: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      infoFiltered: "(Filtrados de _MAX_ registros)",
      infoThousands: ".",
      loadingRecords: "Carregando...",
      zeroRecords: "Nenhum registro encontrado",
      search: "Pesquisar",
      paginate: {
        next: ">",
        previous: "<",
        first: "Primeiro",
        last: "Último",
      },
      aria: {
        sortAscending: ": Ordenar colunas de forma ascendente",
        sortDescending: ": Ordenar colunas de forma descendente",
      },
      select: {
        rows: {
          _: "Selecionado %d linhas",
          1: "Selecionado 1 linha",
        },
        cells: {
          1: "1 célula selecionada",
          _: "%d células selecionadas",
        },
        columns: {
          1: "1 coluna selecionada",
          _: "%d colunas selecionadas",
        },
      },
      buttons: {
        copySuccess: {
          1: "Uma linha copiada com sucesso",
          _: "%d linhas copiadas com sucesso",
        },
        collection:
          'Coleção  <span class="ui-button-icon-primary ui-icon ui-icon-triangle-1-s"></span>',
        colvis: "Colunas",
        colvisRestore: "Restaurar Colunas",
        copy: "Copiar",
        copyKeys:
          "Pressione ctrl ou u2318 + C para copiar os dados da tabela para a área de transferência do sistema. Para cancelar, clique nesta mensagem ou pressione Esc..",
        copyTitle: "Copiar para a Área de Transferência",
        csv: "CSV",
        excel: "Excel",
        pageLength: {
          "-1": "Mostrar todos os registros",
          _: "Mostrar %d registros",
        },
        pdf: "PDF",
        print: "Imprimir",
        createState: "Criar estado",
        removeAllStates: "Remover todos os estados",
        removeState: "Remover",
        renameState: "Renomear",
        savedStates: "Estados salvos",
        stateRestore: "Estado %d",
        updateState: "Atualizar",
      },
      autoFill: {
        cancel: "Cancelar",
        fill: "Preencher todas as células com",
        fillHorizontal: "Preencher células horizontalmente",
        fillVertical: "Preencher células verticalmente",
      },
      lengthMenu: "Exibir _MENU_ resultados por página",
      searchBuilder: {
        add: "Adicionar Condição",
        button: {
          0: "Construtor de Pesquisa",
          _: "Construtor de Pesquisa (%d)",
        },
        clearAll: "Limpar Tudo",
        condition: "Condição",
        conditions: {
          date: {
            after: "Depois",
            before: "Antes",
            between: "Entre",
            empty: "Vazio",
            equals: "Igual",
            not: "Não",
            notBetween: "Não Entre",
            notEmpty: "Não Vazio",
          },
          number: {
            between: "Entre",
            empty: "Vazio",
            equals: "Igual",
            gt: "Maior Que",
            gte: "Maior ou Igual a",
            lt: "Menor Que",
            lte: "Menor ou Igual a",
            not: "Não",
            notBetween: "Não Entre",
            notEmpty: "Não Vazio",
          },
          string: {
            contains: "Contém",
            empty: "Vazio",
            endsWith: "Termina Com",
            equals: "Igual",
            not: "Não",
            notEmpty: "Não Vazio",
            startsWith: "Começa Com",
            notContains: "Não contém",
            notStartsWith: "Não começa com",
            notEndsWith: "Não termina com",
          },
          array: {
            contains: "Contém",
            empty: "Vazio",
            equals: "Igual à",
            not: "Não",
            notEmpty: "Não vazio",
            without: "Não possui",
          },
        },
        data: "Data",
        deleteTitle: "Excluir regra de filtragem",
        logicAnd: "E",
        logicOr: "Ou",
        title: {
          0: "Construtor de Pesquisa",
          _: "Construtor de Pesquisa (%d)",
        },
        value: "Valor",
        leftTitle: "Critérios Externos",
        rightTitle: "Critérios Internos",
      },
      searchPanes: {
        clearMessage: "Limpar Tudo",
        collapse: {
          0: "Painéis de Pesquisa",
          _: "Painéis de Pesquisa (%d)",
        },
        count: "{total}",
        countFiltered: "{shown} ({total})",
        emptyPanes: "Nenhum Painel de Pesquisa",
        loadMessage: "Carregando Painéis de Pesquisa...",
        title: "Filtros Ativos",
        showMessage: "Mostrar todos",
        collapseMessage: "Fechar todos",
      },
      thousands: ".",
      datetime: {
        previous: "Anterior",
        next: "Próximo",
        hours: "Hora",
        minutes: "Minuto",
        seconds: "Segundo",
        amPm: ["am", "pm"],
        unknown: "-",
        months: {
          0: "Janeiro",
          1: "Fevereiro",
          10: "Novembro",
          11: "Dezembro",
          2: "Março",
          3: "Abril",
          4: "Maio",
          5: "Junho",
          6: "Julho",
          7: "Agosto",
          8: "Setembro",
          9: "Outubro",
        },
        weekdays: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
      },
      editor: {
        close: "Fechar",
        create: {
          button: "Novo",
          submit: "Criar",
          title: "Criar novo registro",
        },
        edit: {
          button: "Editar",
          submit: "Atualizar",
          title: "Editar registro",
        },
        error: {
          system:
            'Ocorreu um erro no sistema (<a target="\\" rel="nofollow" href="\\">Mais informações</a>).',
        },
        multi: {
          noMulti:
            "Essa entrada pode ser editada individualmente, mas não como parte do grupo",
          restore: "Desfazer alterações",
          title: "Multiplos valores",
          info: "Os itens selecionados contêm valores diferentes para esta entrada. Para editar e definir todos os itens para esta entrada com o mesmo valor, clique ou toque aqui, caso contrário, eles manterão seus valores individuais.",
        },
        remove: {
          button: "Remover",
          confirm: {
            _: "Tem certeza que quer deletar %d linhas?",
            1: "Tem certeza que quer deletar 1 linha?",
          },
          submit: "Remover",
          title: "Remover registro",
        },
      },
      decimal: ",",
      stateRestore: {
        creationModal: {
          button: "Criar",
          columns: {
            search: "Busca de colunas",
            visible: "Visibilidade da coluna",
          },
          name: "Nome:",
          order: "Ordernar",
          paging: "Paginação",
          scroller: "Posição da barra de rolagem",
          search: "Busca",
          searchBuilder: "Mecanismo de busca",
          select: "Selecionar",
          title: "Criar novo estado",
          toggleLabel: "Inclui:",
        },
        emptyStates: "Nenhum estado salvo",
        removeConfirm: "Confirma remover %s?",
        removeJoiner: "e",
        removeSubmit: "Remover",
        removeTitle: "Remover estado",
        renameButton: "Renomear",
        renameLabel: "Novo nome para %s:",
        renameTitle: "Renomear estado",
        duplicateError: "Já existe um estado com esse nome!",
        emptyError: "Não pode ser vazio!",
        removeError: "Falha ao remover estado!",
      },
      infoEmpty: "Mostrando 0 até 0 de 0 registro(s)",
      processing: "Carregando...",
      searchPlaceholder: "Buscar registros",
    },
    initComplete: function () {},
  });
  return table;
}

// Inicializa o validateForm
function validateForm(...fieldIds) {
  for (const fieldId of fieldIds) {
    if (!validateField(fieldId)) {
      return false; // Parar a validação se algum campo for inválido
    }
  }
  return true;
}

// Inicializa o validateField
function validateField(fieldId, errorMessage = "Este campo é obrigatório") {
  const field = $(`#${fieldId}`);
  let isInvalid = false;

  // Verificar se o campo é um select e está vazio
  if (field.is("select") && (field.val() === "" || field.val() === null)) {
    isInvalid = true;
  }

  // Verificar se o campo é um radio e nenhum está selecionado
  if (field.is(":radio")) {
    const radioName = field.attr("name");
    if ($(`input[name="${radioName}"]:checked`).length === 0) {
      isInvalid = true;
    }
  }

  // Debug: Log para verificar o estado de isInvalid

  // Verificar se o campo está vazio ou marcado como inválido
  if (field.val() === "" || isInvalid) {
    // Campo inválido, adicionar classe is-invalid e mostrar mensagem de erro

    if (field.is(":radio")) {
      // Para campos de rádio, remover a classe is-invalid de todos os botões
      const radioName = field.attr("name");
      $(`input[name="${radioName}"]`).addClass("is-invalid");
      exibirToastr(errorMessage, "danger");
    } else {
      // Para outros campos, adicionar a classe is-invalid ao campo
      field.addClass("is-invalid");

      // Remover qualquer mensagem de erro existente para o campo
      $(`#${fieldId}-error`).remove();

      // Criar e adicionar a mensagem de erro
      const errorElement = $(
        `<span id="${fieldId}-error" class="error invalid-feedback">${errorMessage}</span>`
      );

      // Verificar se o campo está usando Select2
      if (field.hasClass("select2-hidden-accessible")) {
        const select2Container = field.next(".select2");
        select2Container.find(".select2-selection").addClass("is-invalid");
        select2Container.append(errorElement);
      } else {
        errorElement.insertAfter(field);
      }
    }

    return false;
  } else {
    // Campo válido, remover classe is-invalid e remover mensagem de erro

    if (field.is(":radio")) {
      // Para campos de rádio, remover a classe is-invalid de todos os botões
      const radioName = field.attr("name");
      $(`input[name="${radioName}"]`).removeClass("is-invalid");
    } else {
      // Para outros campos, remover a classe is-invalid do campo
      field.removeClass("is-invalid");

      // Se o campo estiver usando Select2, remover a classe is-invalid do contêiner
      if (field.hasClass("select2-hidden-accessible")) {
        const select2Container = field.next(".select2");
        select2Container.find(".select2-selection").removeClass("is-invalid");
      }

      $(`#${fieldId}-error`).remove();
    }
  }

  return true;
}

// Função para exibir mensagem de erro e redirecionar o foco para o campo correto
function exibirToastr(mensagem, tipo, campo = "") {
  if (tipo == "danger") {
    iziToast.warning({
      title: "Erro!",
      message: mensagem,
      position: "topRight",
      icon: "fas fa-exclamation-triangle",
    });

    if (campo != "") {
      $("#" + campo).focus();
    }
  } else {
    iziToast.success({
      title: "Sucesso!",
      message: mensagem,
      position: "topRight",
      icon: "far fa-check-circle",
    });
  }
}

function sweetConfirmacao(mensagems) {
  return new Promise((resolve, reject) => {
    swal({
      title: "Você tem certeza?",
      text: mensagems,
      icon: "warning",
      buttons: {
        cancel: "Cancelar",
        confirm: "Confirmar",
      },
    }).then((willDelete) => {
      if (willDelete) {
        swal("Poof, alterado 😉!", "", "success");
        resolve(true);
      } else {
        resolve(false);
      }
    });
  });
}

function sweetConfirmacao(confirmacao = "Você confirma essa operação?") {
  return new Promise((resolve, reject) => {
    swal({
      title: "Você tem certeza?",
      text: "Atenção: " + confirmacao,
      icon: "warning",
      buttons: {
        cancel: {
          text: "Cancelar",
          value: null,
          visible: true,
          className: "btn-danger",
          closeModal: true,
        },
        confirm: {
          text: "Sim, confirmar!",
          value: true,
          visible: true,
          className: "btn-primary",
          closeModal: true,
        },
      },
    })
      .then((value) => {
        if (value) {
          resolve(true); // Resolva a Promise com true se confirmado
        } else {
          swal("Cancelado", "Sua ação foi cancelada.", "error");
          resolve(false); // Resolva a Promise com false se cancelado
        }
      })
      .catch((err) => {
        reject(err); // Rejeite a Promise em caso de erro
      });
  });
}

function moeda(a, e, r, t) {
  let n = "",
    h = (j = 0),
    u = (tamanho2 = 0),
    l = (ajd2 = ""),
    o = window.Event ? t.which : t.keyCode;
  if (13 == o || 8 == o) return !0;
  if (((n = String.fromCharCode(o)), -1 == "0123456789".indexOf(n))) return !1;
  for (
    u = a.value.length, h = 0;
    h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r);
    h++
  );
  for (l = ""; h < u; h++)
    -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
  if (
    ((l += n),
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2)
  ) {
    for (ajd2 = "", j = 0, h = u - 3; h >= 0; h--)
      3 == j && ((ajd2 += e), (j = 0)), (ajd2 += l.charAt(h)), j++;
    for (a.value = "", tamanho2 = ajd2.length, h = tamanho2 - 1; h >= 0; h--)
      a.value += ajd2.charAt(h);
    a.value += r + l.substr(u - 2, u);
  }
  return !1;
}

function updateRowInTable(tableId, prefix, id, rowData) {
  // Verifica se a tabela já foi inicializada como DataTable
  if (!$.fn.DataTable.isDataTable(tableId)) {
    initDataTable(tableId.substring(1)); // Remove o '#' do ID e inicializa o DataTable
  }

  let table = $(tableId).DataTable();
  let rowId = prefix + id;

  // Verifica se a linha já existe
  let existingRow = table.row(function (idx, data, node) {
    return $(node).attr("id") === rowId;
  });

  if (existingRow.length) {
    // Se a linha já existe, atualize os dados
    existingRow.data(rowData).draw();
  } else {
    // Se a linha não existe, adicione uma nova linha
    table.row.add(rowData).draw();

    // Após adicionar a linha, configura o ID corretamente
    let lastRowNode = $(table.table().body()).find("tr:last");
    lastRowNode.attr("id", rowId);
  }
}

function removeRowFromTable(tableSelector, prefix, id) {
  // Verifica se a tabela foi inicializada como DataTable
  let table = $(tableSelector).DataTable();

  let rowId = prefix + id;

  // Localiza a linha no DataTable e a remove
  table
    .row(function (idx, data, node) {
      return $(node).attr("id") === rowId;
    })
    .remove()
    .draw();
}

function updateTfootFromTable(tableId, columnsToSum) {
  // Verifica se a tabela foi inicializada como DataTable
  let table = $(tableId).DataTable();

  // Inicializa os totais como zero
  let totals = new Array(columnsToSum.length).fill(0);

  // Itera por cada linha visível da tabela e acumula os valores nas colunas especificadas
  table.rows().every(function (rowIdx, tableLoop, rowLoop) {
    let data = this.data();
    columnsToSum.forEach(function (colIndex, i) {
      let value =
        parseFloat(
          data[colIndex]
            .replace(/\./g, "")
            .replace(",", ".")
            .replace("R$", "")
            .trim()
        ) || 0;
      totals[i] += value;
    });
  });

  // Preenche o tfoot com os valores totais
  let tfoot = $(tableId).find("tfoot");

  // Se o tfoot ainda não tiver sido criado, cria a linha e as células
  if (tfoot.find("tr").length === 0) {
    let footerRow = $("<tr>");
    $(tableId)
      .find("thead tr th")
      .each(function () {
        footerRow.append("<th></th>"); // Cria células vazias para cada coluna
      });
    tfoot.append(footerRow);
  }

  // Atualiza as células no tfoot com os totais
  columnsToSum.forEach(function (colIndex, i) {
    tfoot.find("th").eq(colIndex).text(moedaFormat(totals[i])); // Atualiza com o total da coluna
  });
}

// Função para formatar como moeda brasileira
function moedaFormat(valor) {
  // Se o valor não for um número, converte-o para número
  if (typeof valor !== "number") {
    valor = parseFloat(valor);
  }
  // Formata o número para o formato monetário brasileiro
  return valor
    .toLocaleString("pt-BR", {
      style: "currency",
      currency: "BRL",
    })
    .replace("R$", "")
    .trim();
}

// Função para formatar como moeda brasileira
function numberFormat(valor) {
  if (!valor) {
    valor = 0;
  }

  if (typeof valor !== "number") {
    valor = parseFloat(
      valor
        .toString()
        .replace(/\./g, "")
        .replace(",", ".")
        .replace("R$", "")
        .replace("%", "")
        .trim()
    );
  }

  return valor;
}

// Função para formatar a data de "AAAA-MM-DD HH:mm:ss" para "DD/MM/AAAA às HH:mm"
function formatDate(dateTime) {
  // Verifica se dateTime está definido e é uma string
  if (typeof dateTime !== "string" || !dateTime) {
    console.error("Data inválida:", dateTime);
    return ""; // Retorna uma string vazia ou você pode optar por lançar um erro
  }

  let [datePart, timePart] = dateTime.split(" "); // Separa a data e o tempo
  let [year, month, day] = datePart.split("-"); // Separa ano, mês e dia
  let [hours, minutes] = timePart ? timePart.split(":") : ["00", "00"]; // Separa horas e minutos (definindo padrão se não houver hora)

  // Verifica se a data tem o formato esperado
  if (year && month && day && hours && minutes) {
    return `${day}/${month}/${year} às ${hours}:${minutes}`; // Retorna no formato desejado
  } else {
    console.error("Formato de data inválido:", dateTime);
    return ""; // Retorna uma string vazia ou você pode optar por lançar um erro
  }
}