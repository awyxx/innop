Imports MySql.Data.MySqlClient
Imports System.IO
Module utils

    '' variaveis globais
    Public Class g
        Public Shared np As String
        Public Shared cc As String
        Public Shared max_lic As Integer
    End Class

    Dim con As MySqlConnection = connect_db()

    '' retorna connection handle com o mysql
    Public Function connect_db() As MySqlConnection
        Return New MySqlConnection("SERVER=localhost;DATABASE=innoplus;UID=root;PASSWORD=;Convert Zero Datetime=True;")
    End Function


    '' retorna true se o resultado da query nao for nulo (as vzs xddd)
    Function is_result_not_null(ByRef con As MySqlConnection, ByVal query As String) As Boolean

        Dim cmd = New MySqlCommand(query, con)
        Dim valido = cmd.ExecuteReader()

        If Not valido.HasRows Then
            valido.Close()
            Return False
        Else
            valido.Close()
            Return True
        End If

    End Function


    '' verifica o nosso login, da return true se for valido, se nao dá false
    Function check_login(ByVal np As String, ByVal cc As String) As Boolean
        con.Open()

        Dim query As String = "SELECT codprof, cc, nome FROM professor INNER JOIN cartao ON codprof = codcartao WHERE cc ='" & cc & "' AND codprof ='" & np & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim row_col As DataTable = New DataTable()
                adapter.Fill(row_col)
                formlogin.Hide()

                If (get_status(np, cc) <> 2) Then
                    MsgBox("Login efetuado. Bem vindo ' " & row_col(0)(2) & " ' !", vbInformation, "INNOP - Bem vindo")
                    con.Close()
                    index.Show()
                Else
                    con.Close()
                    redirecionar_adminpage.Show()
                End If
                Return True
            Else
                con.Close()
                Return False
            End If

        Catch ex As Exception
            MsgBox("ERRO ex.[check_login()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try
    End Function


    '' vai buscar os nossos dados para o painel dados (superior direito)
    Function get_dados_info(ByVal np As String, ByVal cc As String) As Boolean
        con.Open()

        'info pessoal e dados
        Dim query As String = "SELECT * FROM professor INNER JOIN cartao ON codprof = codcartao WHERE cc ='" & cc & "' AND codprof ='" & np & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim row_col As DataTable = New DataTable()
                adapter.Fill(row_col)

                ' dados
                index.lblCodCartao.Text = row_col(0)(0)
                index.lblNome.Text = row_col(0)(2)
                index.lblStatus.Text = row_col(0)(10)
                index.lblSaldo.Text = row_col(0)(11) & " €"

                index.PictureBox2.Image = get_foto(row_col)
                index.PictureBox2.Size = New Point(81, 76)
                index.PictureBox2.Location = New Point(290, 3)

                ' info pessoal
                index.lblEmail.Text = row_col(0)(7)
                index.lblTelemovel.Text = row_col(0)(6)
                index.lblNacionalidade.Text = row_col(0)(5)
            End If

        Catch ex3 As Exception
            MsgBox("ERRO ex3.[get_dados_info()]: " & ex3.Message, vbCritical)
            Return False
        End Try


        ' info escolar
        ' sua turma
        'lblTurmaDt
        Dim query_turma As String = "SELECT codturma, sigla, ano, curso, nome FROM turmas INNER JOIN (dt INNER JOIN professor ON dt.codprof = professor.codprof) ON turmas.coddt = dt.coddt WHERE professor.codprof =" & np
        Try
            If is_result_not_null(con, query_turma) Then
                Dim cmd_turmas As MySqlCommand = New MySqlCommand(query_turma, con)
                Dim adapter_turmas As New MySqlDataAdapter(cmd_turmas)
                Dim row_col_turmas As DataTable = New DataTable()
                adapter_turmas.Fill(row_col_turmas)

                index.lblTurmaDt.Text = "[ " & row_col_turmas(0)(2) & "º" & row_col_turmas(0)(1) & " ] " & row_col_turmas(0)(3)
            End If

        Catch ex1 As Exception
            MsgBox("ERRO ex1.[get_dados_info()]: " & ex1.Message, vbCritical)
            Return False
        End Try


        'turmas
        Dim query_turmas As String = "SELECT codturma, sigla, ano, curso, nome FROM turmas INNER JOIN (dt INNER JOIN professor ON dt.codprof = professor.codprof) ON turmas.coddt = dt.coddt"
        Try
            If is_result_not_null(con, query_turmas) Then
                Dim cmd_turmas As MySqlCommand = New MySqlCommand(query_turmas, con)
                Dim adapter_turmas As New MySqlDataAdapter(cmd_turmas)
                Dim row_col_turmas As DataTable = New DataTable()
                adapter_turmas.Fill(row_col_turmas)

                ' codturma sigla ano curso dt
                For i As Integer = 0 To get_quant_turmas() - 1 Step 1
                    Dim turma As String
                    turma = "[ " & row_col_turmas(i)(2) & "º" & row_col_turmas(i)(1) & " ] " & row_col_turmas(i)(3) & " DT -> " & row_col_turmas(i)(4)
                    index.listboxTurmas.Items.Add(turma)
                Next
            End If

        Catch ex1 As Exception
            MsgBox("ERRO ex1.[get_dados_info()]: " & ex1.Message, vbCritical)
            Return False
        End Try


        'ultimo sumario
        Dim query_sumario As String = "SELECT coddisciplina, licao, sumario FROM sumarios WHERE codprof = '" & np & "' ORDER BY diasemana DESC"
        Try
            If is_result_not_null(con, query_sumario) Then
                Dim cmd_sumario As MySqlCommand = New MySqlCommand(query_sumario, con)
                Dim adapter_sumario As New MySqlDataAdapter(cmd_sumario)
                Dim row_col_sumario As DataTable = New DataTable()
                adapter_sumario.Fill(row_col_sumario)

                Dim disciplina As String = get_disciplina(row_col_sumario(0)(0))
                index.lblUltimoSumario.Text = "[" & disciplina & "] Lição " & row_col_sumario(0)(1) & " -> " & row_col_sumario(0)(2)
            End If

        Catch ex2 As Exception
            MsgBox("ERRO ex2.[get_dados_info()]: " & ex2.Message, vbCritical)
            Return False
        End Try

        con.Close()
        Return True
    End Function


    '' vai buscar o nosso horario e dá fill à data grid com ele
    Function fill_datagrid_horario() As Boolean
        con.Open()

        Try
            '' preencher
            Dim codhorario = get_codhorario()
            Dim sql As String = "SELECT hora, seg, ter, qua, qui, sex FROM horarios INNER JOIN professor ON horarios.codhorario = professor.codhorario WHERE professor.codhorario = '" & codhorario & "' AND professor.codprof = '" & g.np & "' ORDER BY hora ASC"
            Dim cmd As MySqlCommand = New MySqlCommand(sql, con)
            Dim da As New MySqlDataAdapter(cmd)
            Dim dt As DataTable = New DataTable

            index.DataGridView1.RowTemplate.Height = 40
            index.DataGridView1.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill
            da.Fill(dt)
            index.DataGridView1.DataSource = dt

            '' design

            index.DataGridView1.EnableHeadersVisualStyles = False
            ' linhas e colunas
            index.DataGridView1.DefaultCellStyle.BackColor = Color.FromArgb(204, 255, 255)
            index.DataGridView1.RowsDefaultCellStyle.BackColor = Color.FromArgb(204, 255, 255)
            index.DataGridView1.DefaultCellStyle.Font = New Font("Gotham Black", 9.5)
            index.DataGridView1.RowsDefaultCellStyle.Font = New Font("Gotham Black", 9.5)
            ' headers
            index.DataGridView1.ColumnHeadersDefaultCellStyle.BackColor = Color.FromArgb(51, 204, 255)
            index.DataGridView1.RowHeadersDefaultCellStyle.BackColor = Color.FromArgb(51, 204, 255)
            index.DataGridView1.ColumnHeadersDefaultCellStyle.Font = New Font("Gotham Black", 14)
            index.DataGridView1.RowHeadersDefaultCellStyle.Font = New Font("Gotham Black", 14)

            index.DataGridView1.Columns.RemoveAt(0) ' remover a hora tmb (dar fix doutra maneira???)

        Catch ex As Exception
            MsgBox("ERRO fill_datagrid_horario(): " & ex.Message, vbCritical, "INNOP - ERRO")
            Return False
        End Try

        con.Close()
        Return True
    End Function


    ' da return da quantidade de turma q temos
    Function get_quant_turmas() As Integer

        Dim sql As String = "SELECT count(codturma) FROM turmas"
        Dim cmd As MySqlCommand = New MySqlCommand(sql, con)
        Dim da As New MySqlDataAdapter(cmd)
        Dim dt As DataTable = New DataTable
        Try
            da.Fill(dt)
            Dim quant As Integer = dt(0)(0)
            Return quant
        Catch ex As Exception
            MsgBox("ERRO [get_quant_turmas()]: " & ex.Message, vbCritical)
            Return 0
        End Try
        con.Close()
    End Function


    ' da return à string da disciplina pelo seu coddisciplina
    Function get_disciplina(ByVal coddisci As Integer) As String
        Dim sql As String = "SELECT nome FROM disciplina WHERE coddisciplina = " & coddisci
        Dim cmd As MySqlCommand = New MySqlCommand(sql, con)
        Dim da As New MySqlDataAdapter(cmd)
        Dim dt As DataTable = New DataTable
        Try
            da.Fill(dt)
            Dim nome As String = dt(0)(0)
            Return nome
        Catch ex As Exception
            MsgBox("ERRO [get_disciplina()]: " & ex.Message, vbCritical)
            Return ""
        End Try

    End Function

    ' da return à foto do prof
    Function get_foto(ByVal result As DataTable) As Image
        Try
            Dim imgbyte As Byte()
            imgbyte = result(0)(8)
            Dim ms As New MemoryStream(imgbyte)
            Return Image.FromStream(ms)
        Catch ex As Exception
            MsgBox(ex.Message)
        End Try
    End Function


    ' da reset às labels
    Function reset_vars() As Integer
        index.lblCodCartao.Text = ""
        index.lblNome.Text = ""
        index.lblStatus.Text = ""
        index.lblSaldo.Text = ""

        index.lblEmail.Text = ""
        index.lblTelemovel.Text = ""
        index.lblNacionalidade.Text = ""

        index.listboxTurmas.Items.Clear()

        index.lblUltimoSumario.Text = ""
    End Function


    ' abre a pag de administração no browser
    Function abrir_admin_page() As Boolean
        'MsgBox("Login efetuado. Bem vindo Administrador :)", vbInformation, "INNOP - Bem vindo")
        Try
            'timer 
            Dim adminPage As String = "http://localhost/innop/admin_db/menu.php"
            Process.Start(adminPage)
            Return True
        Catch ex As Exception
            MsgBox("ERRO [abrir_admin_page()] Mova os ficheiros innop para localhost!", vbCritical, "INNOP - ERRO")
            Return False
        End Try
    End Function


    ' 1 = normal , 2 = admin
    Function get_status(ByVal cc As String, ByVal np As String) As Integer
        Dim query As String = "SELECT status FROM professor INNER JOIN cartao ON codprof = codcartao WHERE cc ='" & cc & "' AND codprof ='" & np & "'"
        Try
            If is_result_not_null(con, query) Then

                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim row_col As DataTable = New DataTable()
                adapter.Fill(row_col)

                If (row_col(0)(0) = "Admin") Then
                    con.Close()
                    Return 2
                Else
                    con.Close()
                    Return 1
                End If
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_status()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function


    ' preenche as comboboxes turmas
    Function fill_combobox_turma(ByVal np As Integer) As Boolean
        con.Open()

        Try
            'turmas
            Dim query_turmas As String = "SELECT ano, sigla FROM turmas"
            Dim cmd_turmas As MySqlCommand = New MySqlCommand(query_turmas, con)
            Dim da_turmas As New MySqlDataAdapter(cmd_turmas)
            Dim turmas As DataTable = New DataTable
            da_turmas.Fill(turmas)

            For i As Integer = 0 To get_turmas_count() - 1 Step 1
                index.cbTurma.Items.Add(turmas(i)(0) & " º " & turmas(i)(1))
            Next

        Catch ex As Exception
            MsgBox("ERRO [fill_combobox_turma()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try

        con.Close()
        Return True
    End Function


    'da fill a combo box disciplina
    Function fill_combobox_disciplina(ByVal ano As String) As Boolean
        con.Open()

        Try
            'disciplinas
            index.cbDisciplina.Enabled = True
            Dim query_disciplinas As String = "SELECT nome FROM disciplina WHERE codprof = '" & g.np & "' AND ano = '" & ano & "'"
            Dim cmd_disciplinas As MySqlCommand = New MySqlCommand(query_disciplinas, con)
            Dim da_disciplinas As New MySqlDataAdapter(cmd_disciplinas)
            Dim disciplinas As DataTable = New DataTable
            da_disciplinas.Fill(disciplinas)

            For i As Integer = 0 To get_disciplinas_count(g.np, ano) - 1
                index.cbDisciplina.Items.Add(disciplinas(0)(0))
            Next

        Catch ex As Exception
            MsgBox("ERRO [fill_combobox_disciplina()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try


        con.Close()
        Return True
    End Function


    ' fill a combo box das licoes
    Function fill_combobox_licoes() As Boolean
        con.Open()

        Try
            'disciplinas
            Dim turma As String = index.cbTurma.SelectedItem
            Dim coddisci = get_coddisciplina(turma(0) & turma(1), index.cbDisciplina.SelectedItem)

            index.cbDisciplina.Enabled = True
            Dim query_lic As String = "SELECT licao FROM sumarios WHERE coddisciplina = '" & coddisci & "' AND codprof = '" & g.np & "' AND codturma = '" & get_codturma(turma(0) & turma(1), turma(5)) & "'"
            Dim cmd_lic As MySqlCommand = New MySqlCommand(query_lic, con)
            Dim da_lic As New MySqlDataAdapter(cmd_lic)
            Dim lic As DataTable = New DataTable
            da_lic.Fill(lic)

            'If is_result_not_null(con, query_lic) Then
            'MsgBox("null")
            'End If

            g.max_lic = max_licao(coddisci, get_codturma(turma(0) & turma(1), turma(5)))

            For i As Integer = 0 To g.max_lic - 1
                index.cbLicao.Items.Add(lic(i)(0))
            Next

            index.cbLicao.Items.Add(g.max_lic + 1)

        Catch ex As Exception
            MsgBox("ERRO [fill_combobox_licoes()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try

        con.Close()
        Return True
    End Function


    ' busca sumario
    Function get_sumario() As Boolean
        con.Open()

        Try
            Dim turma As String = index.cbTurma.SelectedItem
            Dim coddisci = get_coddisciplina(turma(0) & turma(1), index.cbDisciplina.SelectedItem)
            Dim codturma = get_codturma(turma(0) & turma(1), turma(5))
            Dim licao = index.cbLicao.SelectedItem

            'Select sumario, diasemana FROM sumarios WHERE codprof = $codprof And codturma = $codturma 
            'And coddisciplina = $coddisciplina And licao = $licao

            Dim query As String = "SELECT sumario, diasemana FROM sumarios WHERE codprof = '" & g.np & "' And codturma = '" & codturma & "' AND coddisciplina = '" & coddisci & "' AND licao = '" & licao & "'"
            Dim cmd As MySqlCommand = New MySqlCommand(query, con)
            Dim da As New MySqlDataAdapter(cmd)
            Dim dt As DataTable = New DataTable
            da.Fill(dt)

            index.textboxSumario.Text = dt(0)(0)
            index.gbSumario.Text = index.gbSumario.Text & " - " & dt(0)(1)

        Catch ex As Exception
            MsgBox("ERRO [get_sumario()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try

        con.Close()
        Return True
    End Function


    ' marca falta
    Function inserir_falta() As Boolean
        con.Open()

        Try
            Dim turma As String = index.cbTurma.SelectedItem
            Dim aluno As String = index.ListBox1.SelectedItem
            Dim codaluno = aluno(0) & aluno(1) & aluno(2) & aluno(3)
            Dim coddisci = get_coddisciplina(turma(0) & turma(1), index.cbDisciplina.SelectedItem)

            Dim tipo_falta = InputBox("Tipo de falta (presença, material, pontualidade):", "INNOP - TIPO DE FALTA")

            If tipo_falta = "" Or tipo_falta = " " Then
                Return False
            End If

            Dim query = "INSERT INTO `faltas`(`codfalta`, `codaluno`, `datafalta`, `diasemana`, `idxhora`, `coddisciplina`, `tipofalta`) VALUES (NULL, '" & codaluno & "', CURRENT_DATE(), DAYOFWEEK(CURRENT_DATE()), CURRENT_TIME(), '" & coddisci & "','" & tipo_falta & "')"
            Dim cmd As MySqlCommand = New MySqlCommand(query, con)
            Dim da As New MySqlDataAdapter(cmd)
            Dim dt As DataTable = New DataTable
            da.Fill(dt)

        Catch ex As Exception
            MsgBox("ERRO [inserir_falta()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try


        Return True
        con.Close()

    End Function

    ' busca sumario
    Function get_turma_toda() As Boolean
        con.Open()

        Try
            Dim turma As String = index.cbTurma.SelectedItem
            Dim coddisci = get_coddisciplina(turma(0) & turma(1), index.cbDisciplina.SelectedItem)
            Dim codturma = get_codturma(turma(0) & turma(1), turma(5))
            Dim licao = index.cbLicao.SelectedItem

            'SELECT turma.numaluno, aluno.nome, turma.codaluno, aluno.nacionalidade, aluno.telemovel, aluno.email FROM turma INNER JOIN aluno 
            'On turma.numaluno = aluno.codaluno WHERE turma.codturma = $codturma

            Dim query As String = "SELECT turma.numaluno, aluno.nome, turma.codaluno, aluno.nacionalidade, aluno.telemovel, aluno.email FROM turma INNER JOIN aluno On turma.numaluno = aluno.codaluno WHERE turma.codturma ='" & codturma & "' ORDER BY turma.codaluno"
            Dim cmd As MySqlCommand = New MySqlCommand(query, con)
            Dim da As New MySqlDataAdapter(cmd)
            Dim dt As DataTable = New DataTable
            da.Fill(dt)

            For i As Integer = 0 To get_quant_alunos(codturma) - 1 Step 1
                Dim aluno = dt(i)(0) & " - " & dt(i)(1) & " - " & dt(i)(3) & " - " & dt(i)(4) & " - " & dt(i)(5)
                index.ListBox1.Items.Add(aluno)
            Next

        Catch ex As Exception
            MsgBox("ERRO [get_turma_toda()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try

        con.Close()
        Return True
    End Function


    'return ao n de alunos de uma turma
    Function get_quant_alunos(ByVal codturma As Integer)
        con.Open()

        Dim query As String = "SELECT COUNT(codaluno) FROM turma WHERE codturma = '" & codturma & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_quant_alunos()]: " & ex.Message, vbCritical)
            con.Close()
            Return 0
        End Try
        con.Close()

    End Function


    ' introduzir sumario
    Function insert_sumario() As Boolean
        ' INSERT INTO `sumarios`(`codprof`, `codturma`, `licao`, `sumario`, `hora`, `diasemana`, `coddisciplina`) VALUES($codprof,$codturma,$licao,'$sumario',CURTIME(),CURDATE(),$coddisciplina)
        con.Open()

        Try
            Dim turma As String = index.cbTurma.SelectedItem
            Dim coddisci = get_coddisciplina(turma(0) & turma(1), index.cbDisciplina.SelectedItem)
            Dim codturma = get_codturma(turma(0) & turma(1), turma(5))
            Dim licao = index.cbLicao.SelectedItem

            Dim query As String = "INSERT INTO `sumarios` (`codprof`, `codturma`, `licao`, `sumario`, `hora`, `diasemana`, `coddisciplina`) VALUES ('" & g.np & "', '" & codturma & "', '" & licao & "', '" & index.textboxSumario.Text & "',CURRENT_TIME(),CURRENT_DATE,'" & coddisci & "')"
            Dim cmd As MySqlCommand = New MySqlCommand(query, con)
            Dim da As New MySqlDataAdapter(cmd)
            Dim dt As DataTable = New DataTable
            da.Fill(dt)

        Catch ex As Exception
            MsgBox("ERRO [insert_sumario()]: " & ex.Message, vbCritical)
            con.Close()
            Return False
        End Try

        con.Close()
        Return True

    End Function


    ' da reutrn Á max licao
    Function max_licao(ByVal coddisciplina As Integer, ByVal codturma As Integer) As Integer
        con.Open()
        Dim query As String = "SELECT MAX(licao) FROM sumarios WHERE codprof = '" & g.np & "' AND coddisciplina = '" & coddisciplina & "' AND codturma = '" & codturma & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                con.Close()
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [max_licao()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function


    ' da return ao numero max de disciplinas
    Function get_disciplinas_count(ByVal np As Integer, ByVal ano As String) As Integer
        Dim query As String = "SELECT COUNT(coddisciplina) FROM disciplina WHERE codprof = '" & np & "' AND ano = '" & ano & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_disciplinas_count()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function


    ' da return a cod disciplina
    Function get_coddisciplina(ByVal ano As String, ByVal nome As String) As Integer
        Dim query As String = "SELECT coddisciplina FROM disciplina WHERE ano = '" & ano & "' AND codprof = '" & g.np & "' AND nome = '" & nome & "'"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                con.Close()
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_coddisciplina()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function


    'return ao cod horario do stor logado
    Function get_codhorario() As Integer
        Dim query As String = "SELECT codhorario FROM professor WHERE codprof = '" & g.np & "' "
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                con.Close()
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_codhorario()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function

    ' da return ao codturma pela siga e ano
    Function get_codturma(ByVal ano As String, ByVal sigla As String) As Integer
        con.Open()
        Dim query As String = "SELECT codturma FROM turmas WHERE ano = '" & ano & "' AND sigla = '" & sigla & "' "
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                con.Close()
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_codturma()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function


    ' da return ao numero de turmas TOTAIS existentes na tabela
    Function get_turmas_count() As Integer
        Dim query As String = "SELECT COUNT(codturma) FROM turmas"
        Try
            If is_result_not_null(con, query) Then
                Dim cmd As MySqlCommand = New MySqlCommand(query, con)
                Dim adapter As New MySqlDataAdapter(cmd)
                Dim n As DataTable = New DataTable()
                adapter.Fill(n)
                Return n(0)(0)
            End If
        Catch ex As Exception
            MsgBox("ERRO [get_turmas_count()]: " & ex.Message, vbCritical)
            Return 0
        End Try
    End Function

End Module
