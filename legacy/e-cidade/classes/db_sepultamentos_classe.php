<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: cemiterio
//CLASSE DA ENTIDADE sepultamentos
class cl_sepultamentos {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $cm01_i_codigo = 0;
   var $cm01_i_medico = 0;
   var $cm01_i_hospital = 0;
   var $cm01_i_funeraria = 0;
   var $cm01_i_causa = 0;
   var $cm01_i_funcionario = 0;
   var $cm01_i_cemiterio = 0;
   var $cm01_i_declarante = 0;
   var $cm01_c_conjuge = null;
   var $cm01_c_cor = null;
   var $cm01_d_falecimento_dia = null;
   var $cm01_d_falecimento_mes = null;
   var $cm01_d_falecimento_ano = null;
   var $cm01_d_falecimento = null;
   var $cm01_c_local = null;
   var $cm01_c_cartorio = null;
   var $cm01_c_livro = null;
   var $cm01_i_folha = 0;
   var $cm01_i_registro = 0;
   var $cm01_d_cadastro_dia = null;
   var $cm01_d_cadastro_mes = null;
   var $cm01_d_cadastro_ano = null;
   var $cm01_d_cadastro = null;
   var $cm01_c_sexo = null;
   var $cm01_observacoes = null;
   var $cm01_c_nomemedico = null;
   var $cm01_c_nomehospital = null;
   var $cm01_c_nomefuneraria = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm01_i_codigo = int4 = Sepultado
                 cm01_i_medico = int4 = Médico
                 cm01_i_hospital = int4 = Hospital
                 cm01_i_funeraria = int4 = Funeraria
                 cm01_i_causa = int4 = Causa
                 cm01_i_funcionario = int4 = Funcionário
                 cm01_i_cemiterio = int4 = Cemitério
                 cm01_i_declarante = int4 = Declarante
                 cm01_c_conjuge = char(40) = Conjuge
                 cm01_c_cor = char(1) = Etnia
                 cm01_d_falecimento = date = Falecimento
                 cm01_c_local = char(40) = Local
                 cm01_c_cartorio = char(40) = Cartório
                 cm01_c_livro = char(6) = Livro
                 cm01_i_folha = int4 = Folha
                 cm01_i_registro = int4 = Registro
                 cm01_d_cadastro = date = Cadastro
                 cm01_c_sexo = char(1) = Sexo
                 cm01_observacoes = text = Observação
                 cm01_c_nomemedico = varchar(60) = Médico
                 cm01_c_nomehospital = varchar(60) = Hospital
                 cm01_c_nomefuneraria = varchar(60) = Funerária
                 ";
   //funcao construtor da classe
   function cl_sepultamentos() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sepultamentos");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->cm01_i_codigo = ($this->cm01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_codigo"]:$this->cm01_i_codigo);
       $this->cm01_i_medico = ($this->cm01_i_medico == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_medico"]:$this->cm01_i_medico);
       $this->cm01_i_hospital = ($this->cm01_i_hospital == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_hospital"]:$this->cm01_i_hospital);
       $this->cm01_i_funeraria = ($this->cm01_i_funeraria == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_funeraria"]:$this->cm01_i_funeraria);
       $this->cm01_i_causa = ($this->cm01_i_causa == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_causa"]:$this->cm01_i_causa);
       $this->cm01_i_funcionario = ($this->cm01_i_funcionario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_funcionario"]:$this->cm01_i_funcionario);
       $this->cm01_i_cemiterio = ($this->cm01_i_cemiterio == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_cemiterio"]:$this->cm01_i_cemiterio);
       $this->cm01_i_declarante = ($this->cm01_i_declarante == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_declarante"]:$this->cm01_i_declarante);
       $this->cm01_c_conjuge = ($this->cm01_c_conjuge == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_conjuge"]:$this->cm01_c_conjuge);
       $this->cm01_c_cor = ($this->cm01_c_cor == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_cor"]:$this->cm01_c_cor);
       if($this->cm01_d_falecimento == ""){
         $this->cm01_d_falecimento_dia = ($this->cm01_d_falecimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_dia"]:$this->cm01_d_falecimento_dia);
         $this->cm01_d_falecimento_mes = ($this->cm01_d_falecimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_mes"]:$this->cm01_d_falecimento_mes);
         $this->cm01_d_falecimento_ano = ($this->cm01_d_falecimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_ano"]:$this->cm01_d_falecimento_ano);
         if($this->cm01_d_falecimento_dia != ""){
            $this->cm01_d_falecimento = $this->cm01_d_falecimento_ano."-".$this->cm01_d_falecimento_mes."-".$this->cm01_d_falecimento_dia;
         }
       }
       $this->cm01_c_local = ($this->cm01_c_local == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_local"]:$this->cm01_c_local);
       $this->cm01_c_cartorio = ($this->cm01_c_cartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_cartorio"]:$this->cm01_c_cartorio);
       $this->cm01_c_livro = ($this->cm01_c_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_livro"]:$this->cm01_c_livro);
       $this->cm01_i_folha = ($this->cm01_i_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_folha"]:$this->cm01_i_folha);
       $this->cm01_i_registro = ($this->cm01_i_registro == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_registro"]:$this->cm01_i_registro);
       if($this->cm01_d_cadastro == ""){
         $this->cm01_d_cadastro_dia = ($this->cm01_d_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_dia"]:$this->cm01_d_cadastro_dia);
         $this->cm01_d_cadastro_mes = ($this->cm01_d_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_mes"]:$this->cm01_d_cadastro_mes);
         $this->cm01_d_cadastro_ano = ($this->cm01_d_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_ano"]:$this->cm01_d_cadastro_ano);
         if($this->cm01_d_cadastro_dia != ""){
            $this->cm01_d_cadastro = $this->cm01_d_cadastro_ano."-".$this->cm01_d_cadastro_mes."-".$this->cm01_d_cadastro_dia;
         }
       }
       $this->cm01_c_sexo = ($this->cm01_c_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_sexo"]:$this->cm01_c_sexo);
       $this->cm01_observacoes = ($this->cm01_observacoes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_observacoes"]:$this->cm01_observacoes);
       $this->cm01_c_nomemedico = ($this->cm01_c_nomemedico == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_nomemedico"]:$this->cm01_c_nomemedico);
       $this->cm01_c_nomehospital = ($this->cm01_c_nomehospital == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_nomehospital"]:$this->cm01_c_nomehospital);
       $this->cm01_c_nomefuneraria = ($this->cm01_c_nomefuneraria == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_c_nomefuneraria"]:$this->cm01_c_nomefuneraria);
     }else{
       $this->cm01_i_codigo = ($this->cm01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm01_i_codigo"]:$this->cm01_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($cm01_i_codigo){
      $this->atualizacampos();
     if($this->cm01_i_causa == null ){
       $this->erro_sql = " Campo Causa não informado.";
       $this->erro_campo = "cm01_i_causa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_i_funcionario == null ){
       $this->erro_sql = " Campo Funcionário não informado.";
       $this->erro_campo = "cm01_i_funcionario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_i_cemiterio == null ){
       $this->erro_sql = " Campo Cemitério não informado.";
       $this->erro_campo = "cm01_i_cemiterio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_c_cor == null ){
       $this->erro_sql = " Campo Etnia não informado.";
       $this->erro_campo = "cm01_c_cor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_d_falecimento == null ){
       $this->erro_sql = " Campo Falecimento não informado.";
       $this->erro_campo = "cm01_d_falecimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_c_local == null ){
       $this->erro_sql = " Campo Local não informado.";
       $this->erro_campo = "cm01_c_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm01_c_cartorio == null ){
       $this->erro_sql = " Campo Cartório não informado.";
       $this->erro_campo = "cm01_c_cartorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->cm01_c_livro == null ){
     	 $this->cm01_c_livro    = '';
     }

     if($this->cm01_i_folha == null ){
     	$this->cm01_i_folha     = 'null';
     }

     if($this->cm01_i_registro == null ){
       $this->cm01_i_registro = 'null';
     }

     if($this->cm01_i_medico == null ){
       $this->cm01_i_medico    = 'null';
     }

     if($this->cm01_i_hospital == null ){
      $this->cm01_i_hospital     = 'null';
     }

     if($this->cm01_i_funeraria == null ){
       $this->cm01_i_funeraria = 'null';
     }

     if($this->cm01_i_declarante == null ){
       $this->cm01_i_declarante = 'null';
     }

     if($this->cm01_d_cadastro == null ){
       $this->erro_sql = " Campo Cadastro não informado.";
       $this->erro_campo = "cm01_d_cadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cm01_i_codigo = $cm01_i_codigo;
     if(($this->cm01_i_codigo == null) || ($this->cm01_i_codigo == "") ){
       $this->erro_sql = " Campo cm01_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sepultamentos(
                                       cm01_i_codigo
                                      ,cm01_i_medico
                                      ,cm01_i_hospital
                                      ,cm01_i_funeraria
                                      ,cm01_i_causa
                                      ,cm01_i_funcionario
                                      ,cm01_i_cemiterio
                                      ,cm01_i_declarante
                                      ,cm01_c_conjuge
                                      ,cm01_c_cor
                                      ,cm01_d_falecimento
                                      ,cm01_c_local
                                      ,cm01_c_cartorio
                                      ,cm01_c_livro
                                      ,cm01_i_folha
                                      ,cm01_i_registro
                                      ,cm01_d_cadastro
                                      ,cm01_c_sexo
                                      ,cm01_observacoes
                                      ,cm01_c_nomemedico
                                      ,cm01_c_nomehospital
                                      ,cm01_c_nomefuneraria
                       )
                values (
                                $this->cm01_i_codigo
                               ,$this->cm01_i_medico
                               ,$this->cm01_i_hospital
                               ,$this->cm01_i_funeraria
                               ,$this->cm01_i_causa
                               ,$this->cm01_i_funcionario
                               ,$this->cm01_i_cemiterio
                               ,$this->cm01_i_declarante
                               ,'$this->cm01_c_conjuge'
                               ,'$this->cm01_c_cor'
                               ,".($this->cm01_d_falecimento == "null" || $this->cm01_d_falecimento == ""?"null":"'".$this->cm01_d_falecimento."'")."
                               ,'$this->cm01_c_local'
                               ,'$this->cm01_c_cartorio'
                               ,'$this->cm01_c_livro'
                               ,$this->cm01_i_folha
                               ,$this->cm01_i_registro
                               ,".($this->cm01_d_cadastro == "null" || $this->cm01_d_cadastro == ""?"null":"'".$this->cm01_d_cadastro."'")."
                               ,'$this->cm01_c_sexo'
                               ,'$this->cm01_observacoes'
                               ,'$this->cm01_c_nomemedico'
                               ,'$this->cm01_c_nomehospital'
                               ,'$this->cm01_c_nomefuneraria'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sepultamentos ($this->cm01_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sepultamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sepultamentos ($this->cm01_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->cm01_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cm01_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10387,'$this->cm01_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1797,10387,'','".AddSlashes(pg_result($resaco,0,'cm01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10388,'','".AddSlashes(pg_result($resaco,0,'cm01_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10389,'','".AddSlashes(pg_result($resaco,0,'cm01_i_hospital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10390,'','".AddSlashes(pg_result($resaco,0,'cm01_i_funeraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10391,'','".AddSlashes(pg_result($resaco,0,'cm01_i_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10392,'','".AddSlashes(pg_result($resaco,0,'cm01_i_funcionario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10393,'','".AddSlashes(pg_result($resaco,0,'cm01_i_cemiterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10394,'','".AddSlashes(pg_result($resaco,0,'cm01_i_declarante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10395,'','".AddSlashes(pg_result($resaco,0,'cm01_c_conjuge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10396,'','".AddSlashes(pg_result($resaco,0,'cm01_c_cor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10397,'','".AddSlashes(pg_result($resaco,0,'cm01_d_falecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10398,'','".AddSlashes(pg_result($resaco,0,'cm01_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10399,'','".AddSlashes(pg_result($resaco,0,'cm01_c_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10400,'','".AddSlashes(pg_result($resaco,0,'cm01_c_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10401,'','".AddSlashes(pg_result($resaco,0,'cm01_i_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10402,'','".AddSlashes(pg_result($resaco,0,'cm01_i_registro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10403,'','".AddSlashes(pg_result($resaco,0,'cm01_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,10404,'','".AddSlashes(pg_result($resaco,0,'cm01_c_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,20278,'','".AddSlashes(pg_result($resaco,0,'cm01_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,22424,'','".AddSlashes(pg_result($resaco,0,'cm01_c_nomemedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,22425,'','".AddSlashes(pg_result($resaco,0,'cm01_c_nomehospital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1797,22426,'','".AddSlashes(pg_result($resaco,0,'cm01_c_nomefuneraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($cm01_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update sepultamentos set ";
     $virgula = "";
     if(trim($this->cm01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_codigo"])){
       $sql  .= $virgula." cm01_i_codigo = $this->cm01_i_codigo ";
       $virgula = ",";
       if(trim($this->cm01_i_codigo) == null ){
         $this->erro_sql = " Campo Sepultado não informado.";
         $this->erro_campo = "cm01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

      // medico
      $cm01_c_nomemedico = "'".$this->cm01_c_nomemedico."'";

      $sql .= $virgula." cm01_c_nomemedico = $cm01_c_nomemedico ";
      $virgula = ",";

      $iMedico = $this->cm01_i_medico;

      if(empty($iMedico)) {
        $iMedico = "null";
      }

      $sql .= $virgula." cm01_i_medico = $iMedico ";
      $virgula = ",";

      // hospital
      $cm01_c_nomehospital = "'".$this->cm01_c_nomehospital."'";

      $sql .= $virgula." cm01_c_nomehospital = $cm01_c_nomehospital ";
      $virgula = ",";

      $iHospital = $this->cm01_i_hospital;

      if (empty($iHospital)) {
        $iHospital = "null";
      }

      $sql .= $virgula." cm01_i_hospital = $iHospital ";
      $virgula = ",";

      // funeraria
      $cm01_c_nomefuneraria = "'".$this->cm01_c_nomefuneraria."'";

      $sql  .= $virgula." cm01_c_nomefuneraria = $cm01_c_nomefuneraria ";
      $virgula = ",";

      $iFuneraria = $this->cm01_i_funeraria;

      if (empty($iFuneraria)) {
        $iFuneraria = "null";
      }

      $sql .= $virgula." cm01_i_funeraria = $iFuneraria ";
      $virgula = ",";

      if(trim($this->cm01_i_causa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_causa"])){
       $sql  .= $virgula." cm01_i_causa = $this->cm01_i_causa ";
       $virgula = ",";
       if(trim($this->cm01_i_causa) == null ){
         $this->erro_sql = " Campo Causa não informado.";
         $this->erro_campo = "cm01_i_causa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
      }

      if(trim($this->cm01_i_funcionario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_funcionario"])){
       $sql  .= $virgula." cm01_i_funcionario = $this->cm01_i_funcionario ";
       $virgula = ",";
       if(trim($this->cm01_i_funcionario) == null ){
         $this->erro_sql = " Campo Funcionário não informado.";
         $this->erro_campo = "cm01_i_funcionario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
      }

     if(trim($this->cm01_i_cemiterio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_cemiterio"])){
       $sql  .= $virgula." cm01_i_cemiterio = $this->cm01_i_cemiterio ";
       $virgula = ",";
       if(trim($this->cm01_i_cemiterio) == null ){
         $this->erro_sql = " Campo Cemitério não informado.";
         $this->erro_campo = "cm01_i_cemiterio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm01_i_declarante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_declarante"])){
       $sql  .= $virgula." cm01_i_declarante = $this->cm01_i_declarante ";
       $virgula = ",";
       if(trim($this->cm01_i_declarante) == null ){
         $this->erro_sql = " Campo Declarante não informado.";
         $this->erro_campo = "cm01_i_declarante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm01_c_conjuge)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_conjuge"])){
       $sql  .= $virgula." cm01_c_conjuge = '$this->cm01_c_conjuge' ";
       $virgula = ",";
     }
     if(trim($this->cm01_c_cor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_cor"])){
       $sql  .= $virgula." cm01_c_cor = '$this->cm01_c_cor' ";
       $virgula = ",";
       if(trim($this->cm01_c_cor) == null ){
         $this->erro_sql = " Campo Etnia não informado.";
         $this->erro_campo = "cm01_c_cor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm01_d_falecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_dia"] !="") ){
       $sql  .= $virgula." cm01_d_falecimento = '$this->cm01_d_falecimento' ";
       $virgula = ",";
       if(trim($this->cm01_d_falecimento) == null ){
         $this->erro_sql = " Campo Falecimento não informado.";
         $this->erro_campo = "cm01_d_falecimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento_dia"])){
         $sql  .= $virgula." cm01_d_falecimento = null ";
         $virgula = ",";
         if(trim($this->cm01_d_falecimento) == null ){
           $this->erro_sql = " Campo Falecimento não informado.";
           $this->erro_campo = "cm01_d_falecimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm01_c_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_local"])){
       $sql  .= $virgula." cm01_c_local = '$this->cm01_c_local' ";
       $virgula = ",";
       if(trim($this->cm01_c_local) == null ){
         $this->erro_sql = " Campo Local não informado.";
         $this->erro_campo = "cm01_c_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm01_c_cartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_cartorio"])){
       $sql  .= $virgula." cm01_c_cartorio = '$this->cm01_c_cartorio' ";
       $virgula = ",";
       if(trim($this->cm01_c_cartorio) == null ){
         $this->erro_sql = " Campo Cartório não informado.";
         $this->erro_campo = "cm01_c_cartorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm01_c_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_livro"])){

      $sLivro = "'".$this->cm01_c_livro."'";

       $sql  .= $virgula." cm01_c_livro = $sLivro ";
       $virgula = ",";
     }
     if(trim($this->cm01_i_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_folha"])){

      $sFolha = $this->cm01_i_folha;

      if(empty($sFolha)) {
        $sFolha = "null";
      }

       $sql  .= $virgula." cm01_i_folha = $sFolha ";
       $virgula = ",";
     }
     if(trim($this->cm01_i_registro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_registro"])){

      $sRegistro = $this->cm01_i_registro;

      if(empty($sRegistro)) {
        $sRegistro = "null";
      }

       $sql  .= $virgula." cm01_i_registro = $sRegistro ";
       $virgula = ",";
     }
     if(trim($this->cm01_d_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_dia"] !="") ){
       $sql  .= $virgula." cm01_d_cadastro = '$this->cm01_d_cadastro' ";
       $virgula = ",";
       if(trim($this->cm01_d_cadastro) == null ){
         $this->erro_sql = " Campo Cadastro não informado.";
         $this->erro_campo = "cm01_d_cadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro_dia"])){
         $sql  .= $virgula." cm01_d_cadastro = null ";
         $virgula = ",";
         if(trim($this->cm01_d_cadastro) == null ){
           $this->erro_sql = " Campo Cadastro não informado.";
           $this->erro_campo = "cm01_d_cadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm01_c_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_sexo"])){
       $sql  .= $virgula." cm01_c_sexo = '$this->cm01_c_sexo' ";
       $virgula = ",";
     }
     if(trim($this->cm01_observacoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm01_observacoes"])){
       $sql  .= $virgula." cm01_observacoes = '$this->cm01_observacoes' ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($cm01_i_codigo!=null){
       $sql .= " cm01_i_codigo = $this->cm01_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cm01_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,10387,'$this->cm01_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_codigo"]) || $this->cm01_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1797,10387,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_codigo'))."','$this->cm01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_medico"]) || $this->cm01_i_medico != "")
             $resac = db_query("insert into db_acount values($acount,1797,10388,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_medico'))."','$this->cm01_i_medico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_hospital"]) || $this->cm01_i_hospital != "")
             $resac = db_query("insert into db_acount values($acount,1797,10389,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_hospital'))."','$this->cm01_i_hospital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_funeraria"]) || $this->cm01_i_funeraria != "")
             $resac = db_query("insert into db_acount values($acount,1797,10390,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_funeraria'))."','$this->cm01_i_funeraria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_causa"]) || $this->cm01_i_causa != "")
             $resac = db_query("insert into db_acount values($acount,1797,10391,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_causa'))."','$this->cm01_i_causa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_funcionario"]) || $this->cm01_i_funcionario != "")
             $resac = db_query("insert into db_acount values($acount,1797,10392,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_funcionario'))."','$this->cm01_i_funcionario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_cemiterio"]) || $this->cm01_i_cemiterio != "")
             $resac = db_query("insert into db_acount values($acount,1797,10393,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_cemiterio'))."','$this->cm01_i_cemiterio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_declarante"]) || $this->cm01_i_declarante != "")
             $resac = db_query("insert into db_acount values($acount,1797,10394,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_declarante'))."','$this->cm01_i_declarante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_conjuge"]) || $this->cm01_c_conjuge != "")
             $resac = db_query("insert into db_acount values($acount,1797,10395,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_conjuge'))."','$this->cm01_c_conjuge',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_cor"]) || $this->cm01_c_cor != "")
             $resac = db_query("insert into db_acount values($acount,1797,10396,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_cor'))."','$this->cm01_c_cor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_falecimento"]) || $this->cm01_d_falecimento != "")
             $resac = db_query("insert into db_acount values($acount,1797,10397,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_d_falecimento'))."','$this->cm01_d_falecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_local"]) || $this->cm01_c_local != "")
             $resac = db_query("insert into db_acount values($acount,1797,10398,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_local'))."','$this->cm01_c_local',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_cartorio"]) || $this->cm01_c_cartorio != "")
             $resac = db_query("insert into db_acount values($acount,1797,10399,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_cartorio'))."','$this->cm01_c_cartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_livro"]) || $this->cm01_c_livro != "")
             $resac = db_query("insert into db_acount values($acount,1797,10400,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_livro'))."','$this->cm01_c_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_folha"]) || $this->cm01_i_folha != "")
             $resac = db_query("insert into db_acount values($acount,1797,10401,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_folha'))."','$this->cm01_i_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_i_registro"]) || $this->cm01_i_registro != "")
             $resac = db_query("insert into db_acount values($acount,1797,10402,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_i_registro'))."','$this->cm01_i_registro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_d_cadastro"]) || $this->cm01_d_cadastro != "")
             $resac = db_query("insert into db_acount values($acount,1797,10403,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_d_cadastro'))."','$this->cm01_d_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_sexo"]) || $this->cm01_c_sexo != "")
             $resac = db_query("insert into db_acount values($acount,1797,10404,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_sexo'))."','$this->cm01_c_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_observacoes"]) || $this->cm01_observacoes != "")
             $resac = db_query("insert into db_acount values($acount,1797,20278,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_observacoes'))."','$this->cm01_observacoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_nomemedico"]) || $this->cm01_c_nomemedico != "")
             $resac = db_query("insert into db_acount values($acount,1797,22424,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_nomemedico'))."','$this->cm01_c_nomemedico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_nomehospital"]) || $this->cm01_c_nomehospital != "")
             $resac = db_query("insert into db_acount values($acount,1797,22425,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_nomehospital'))."','$this->cm01_c_nomehospital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cm01_c_nomefuneraria"]) || $this->cm01_c_nomefuneraria != "")
             $resac = db_query("insert into db_acount values($acount,1797,22426,'".AddSlashes(pg_result($resaco,$conresaco,'cm01_c_nomefuneraria'))."','$this->cm01_c_nomefuneraria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sepultamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sepultamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->cm01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($cm01_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere) || is_null($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cm01_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,10387,'$cm01_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1797,10387,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10388,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_medico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10389,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_hospital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10390,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_funeraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10391,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10392,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_funcionario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10393,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_cemiterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10394,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_declarante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10395,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_conjuge'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10396,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_cor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10397,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_d_falecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10398,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_local'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10399,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_cartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10400,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10401,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10402,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_i_registro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10403,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_d_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,10404,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,20278,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_observacoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,22424,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_nomemedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,22425,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_nomehospital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1797,22426,'','".AddSlashes(pg_result($resaco,$iresaco,'cm01_c_nomefuneraria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sepultamentos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cm01_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cm01_i_codigo = $cm01_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sepultamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sepultamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$cm01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sepultamentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $cm01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from sepultamentos ";
     $sql .= "      inner join cgm  					on  cgm.z01_numcgm 								  = sepultamentos.cm01_i_codigo			 ";
     $sql .= "      left join db_usuarios     on  db_usuarios.id_usuario          = sepultamentos.cm01_i_funcionario ";
     $sql .= "      left join legista         on  legista.cm32_i_codigo           = sepultamentos.cm01_i_medico		   ";
     $sql .= "      left join causa           on  causa.cm04_i_codigo             = sepultamentos.cm01_i_causa			 ";
     $sql .= "      left join cemiterio       on  cemiterio.cm14_i_codigo         = sepultamentos.cm01_i_cemiterio   ";
     $sql .= "      left join hospitais       on  hospitais.cm18_i_hospital       = sepultamentos.cm01_i_hospital    ";
     $sql .= "      left join funerarias      on  funerarias.cm17_i_funeraria     = sepultamentos.cm01_i_funeraria	 ";
     $sql .= "      left  join cemiteriocgm   on  cemiteriocgm.cm15_i_cemiterio   = cemiterio.cm14_i_codigo 				 ";
     $sql .= "      left  join cemiteriorural on  cemiteriorural.cm16_i_cemiterio = cemiterio.cm14_i_codigo 			   ";
     $sql .= "      left join cgm as cgm1     on  cgm1.z01_numcgm                 = hospitais.cm18_i_hospital				 ";
     $sql .= "      left join cgm as cgm2     on  cgm2.z01_numcgm                 = funerarias.cm17_i_funeraria			 ";
     $sql .= "      left join cgm as cgm3     on  cgm3.z01_numcgm                 = sepultamentos.cm01_i_declarante  ";
     $sql .= "      left join cgm as cgm4     on  cgm4.z01_numcgm                 = legista.cm32_i_numcgm					   ";
     $sql .= "      left join cgm as cgm5     on  cgm5.z01_numcgm                 = cemiteriocgm.cm15_i_cgm 				 ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm01_i_codigo!=null ){
         $sql2 .= " where sepultamentos.cm01_i_codigo = $cm01_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($cm01_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sepultamentos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cm01_i_codigo)){
         $sql2 .= " where sepultamentos.cm01_i_codigo = $cm01_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  function sql_query_dados_sepultamento($cm01_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = ""){

    $sql = "select ";

    if($campos != "*" ) {

      $campos_sql = split("#", $campos);

      $virgula = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }

    } else {
      $sql .= $campos;
    }

    $sql .= " from sepultamentos ";
    $sql .= "      inner join cgm           on  cgm.z01_numcgm                  = sepultamentos.cm01_i_codigo      ";
    $sql .= "      left join db_usuarios    on  db_usuarios.id_usuario          = sepultamentos.cm01_i_funcionario ";
    $sql .= "      left join legista        on  legista.cm32_i_codigo           = sepultamentos.cm01_i_medico      ";
    $sql .= "      left join causa          on  causa.cm04_i_codigo             = sepultamentos.cm01_i_causa       ";
    $sql .= "      left join cemiterio      on  cemiterio.cm14_i_codigo         = sepultamentos.cm01_i_cemiterio   ";
    $sql .= "      left join hospitais      on  hospitais.cm18_i_hospital       = sepultamentos.cm01_i_hospital    ";
    $sql .= "      left join funerarias     on  funerarias.cm17_i_funeraria     = sepultamentos.cm01_i_funeraria   ";
    $sql .= "      left join cemiteriocgm   on  cemiteriocgm.cm15_i_cemiterio   = cemiterio.cm14_i_codigo          ";
    $sql .= "      left join cemiteriorural on  cemiteriorural.cm16_i_cemiterio = cemiterio.cm14_i_codigo          ";
    $sql .= "      left join cgm as cgm1    on  cgm1.z01_numcgm                 = hospitais.cm18_i_hospital        ";
    $sql .= "      left join cgm as cgm2    on  cgm2.z01_numcgm                 = funerarias.cm17_i_funeraria      ";
    $sql .= "      left join cgm as cgm3    on  cgm3.z01_numcgm                 = sepultamentos.cm01_i_declarante  ";
    $sql .= "      left join cgm as cgm4    on  cgm4.z01_numcgm                 = legista.cm32_i_numcgm            ";
    $sql .= "      left join cgm as cgm5    on  cgm5.z01_numcgm                 = cemiteriocgm.cm15_i_cgm          ";
    $sql .= "      left join ossoario       on  cm06_i_sepultamento             = cm01_i_codigo                    ";
    $sql .= "      left join sepulta        on  cm24_i_sepultamento             = cm01_i_codigo                    ";
    $sql .= "      left join sepulturas     on  cm05_i_codigo                   = cm24_i_sepultura                 ";
    $sql .= "      left join restosgavetas  on  cm26_i_sepultamento             = cm01_i_codigo                    ";
    $sql .= "      left join gavetas        on  cm27_i_restogaveta              = cm26_i_codigo                    ";

    $sql2 = "";

    if($dbwhere == "") {

      if($cm01_i_codigo != null) {
        $sql2 .= " where sepultamentos.cm01_i_codigo = $cm01_i_codigo ";
      }

    } else if($dbwhere != "") {

      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if($ordem != null) {

      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}
