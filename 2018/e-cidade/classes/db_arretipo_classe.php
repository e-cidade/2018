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

//MODULO: caixa
//CLASSE DA ENTIDADE arretipo
class cl_arretipo {
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
   var $k00_codbco = 0;
   var $k00_codage = null;
   var $k00_tipo = 0;
   var $k00_descr = null;
   var $k00_emrec = 'f';
   var $k00_agnum = 'f';
   var $k00_agpar = 'f';
   var $k00_msguni = null;
   var $k00_msguni2 = null;
   var $k00_msgparc = null;
   var $k00_msgparc2 = null;
   var $k00_msgparcvenc = null;
   var $k00_msgparcvenc2 = null;
   var $k00_msgrecibo = null;
   var $k00_tercdigcarneunica = 0;
   var $k00_tercdigcarnenormal = 0;
   var $k00_tercdigrecunica = 0;
   var $k00_tercdigrecnormal = 0;
   var $k00_txban = 0;
   var $k00_rectx = 0;
   var $codmodelo = 0;
   var $k00_impval = 'f';
   var $k00_vlrmin = 0;
   var $k03_tipo = 0;
   var $k00_marcado = null;
   var $k00_hist1 = null;
   var $k00_hist2 = null;
   var $k00_hist3 = null;
   var $k00_hist4 = null;
   var $k00_hist5 = null;
   var $k00_hist6 = null;
   var $k00_hist7 = null;
   var $k00_hist8 = null;
   var $k00_tipoagrup = 0;
   var $k00_recibodbpref = 0;
   var $k00_instit = 0;
   var $k00_formemissao = 0;
   var $k00_receitacredito = 0;
   var $k00_exercicioscarne = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k00_codbco = int4 = codigo do banco
                 k00_codage = char(5) = codigo da agencia
                 k00_tipo = int4 = Tipo de Débito
                 k00_descr = varchar(40) = descricao do tipo de debito
                 k00_emrec = bool = emite recibo
                 k00_agnum = bool = agrupa pelo numpre
                 k00_agpar = bool = agrupa pelo numpar
                 k00_msguni = text = Mensagem de instrução do carne única
                 k00_msguni2 = text = Mensagem de instrução do carne única via 2
                 k00_msgparc = text = Mensagem de instrução do carne parcelado
                 k00_msgparc2 = text = Mensagem de instrução do carne parcelado via 2
                 k00_msgparcvenc = text = Mensagem de instrução do carne com parcela vencida
                 k00_msgparcvenc2 = text = Mensagem de instrução do carne com parcela vencida
                 k00_msgrecibo = text = Mensagem do recibo
                 k00_tercdigcarneunica = int4 = Terc. digito cod. barras carne única
                 k00_tercdigcarnenormal = int4 = Terc. digito cod. barras carne normal
                 k00_tercdigrecunica = int4 = Terc. digito cod. barras recibo unica
                 k00_tercdigrecnormal = int4 = Terc. digito cod. barras recibo normal
                 k00_txban = float8 = Taxa Bancária
                 k00_rectx = int4 = Receita da Taxa Bancaria
                 codmodelo = int4 = Código Modelo
                 k00_impval = bool = Imprime valor
                 k00_vlrmin = float8 = Valor minimo
                 k03_tipo = int4 = Grupo de  Débito
                 k00_marcado = varchar(1) = Marcado
                 k00_hist1 = varchar(80) = historico do recibo 1
                 k00_hist2 = varchar(80) = historico do recibo 2
                 k00_hist3 = varchar(80) = historico do recibo 3
                 k00_hist4 = varchar(80) = historico do recibo 4
                 k00_hist5 = varchar(80) = historico do recibo 5
                 k00_hist6 = varchar(80) = historico do recibo 6
                 k00_hist7 = varchar(80) = historico do recibo 7
                 k00_hist8 = varchar(80) = historico do recibo 8
                 k00_tipoagrup = int4 = Tipo de agrupamento
                 k00_recibodbpref = int4 = Libera recibo dbpref
                 k00_instit = int4 = Instituição
                 k00_formemissao = int4 = Forma Emissão 2a. Via de Carnês
                 k00_receitacredito = int4 = Receita de Crédito
                 k00_exercicioscarne = int4 = Exercícios do Carne
                 ";
   //funcao construtor da classe
   function cl_arretipo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arretipo");
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
       $this->k00_codbco = ($this->k00_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codbco"]:$this->k00_codbco);
       $this->k00_codage = ($this->k00_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codage"]:$this->k00_codage);
       $this->k00_tipo = ($this->k00_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipo"]:$this->k00_tipo);
       $this->k00_descr = ($this->k00_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_descr"]:$this->k00_descr);
       $this->k00_emrec = ($this->k00_emrec == "f"?@$GLOBALS["HTTP_POST_VARS"]["k00_emrec"]:$this->k00_emrec);
       $this->k00_agnum = ($this->k00_agnum == "f"?@$GLOBALS["HTTP_POST_VARS"]["k00_agnum"]:$this->k00_agnum);
       $this->k00_agpar = ($this->k00_agpar == "f"?@$GLOBALS["HTTP_POST_VARS"]["k00_agpar"]:$this->k00_agpar);
       $this->k00_msguni = ($this->k00_msguni == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msguni"]:$this->k00_msguni);
       $this->k00_msguni2 = ($this->k00_msguni2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msguni2"]:$this->k00_msguni2);
       $this->k00_msgparc = ($this->k00_msgparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msgparc"]:$this->k00_msgparc);
       $this->k00_msgparc2 = ($this->k00_msgparc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msgparc2"]:$this->k00_msgparc2);
       $this->k00_msgparcvenc = ($this->k00_msgparcvenc == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc"]:$this->k00_msgparcvenc);
       $this->k00_msgparcvenc2 = ($this->k00_msgparcvenc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc2"]:$this->k00_msgparcvenc2);
       $this->k00_msgrecibo = ($this->k00_msgrecibo == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_msgrecibo"]:$this->k00_msgrecibo);
       $this->k00_tercdigcarneunica = ($this->k00_tercdigcarneunica == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarneunica"]:$this->k00_tercdigcarneunica);
       $this->k00_tercdigcarnenormal = ($this->k00_tercdigcarnenormal == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarnenormal"]:$this->k00_tercdigcarnenormal);
       $this->k00_tercdigrecunica = ($this->k00_tercdigrecunica == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecunica"]:$this->k00_tercdigrecunica);
       $this->k00_tercdigrecnormal = ($this->k00_tercdigrecnormal == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecnormal"]:$this->k00_tercdigrecnormal);
       $this->k00_txban = ($this->k00_txban == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_txban"]:$this->k00_txban);
       $this->k00_rectx = ($this->k00_rectx == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_rectx"]:$this->k00_rectx);
       $this->codmodelo = ($this->codmodelo == ""?@$GLOBALS["HTTP_POST_VARS"]["codmodelo"]:$this->codmodelo);
       $this->k00_impval = ($this->k00_impval == "f"?@$GLOBALS["HTTP_POST_VARS"]["k00_impval"]:$this->k00_impval);
       $this->k00_vlrmin = ($this->k00_vlrmin == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrmin"]:$this->k00_vlrmin);
       $this->k03_tipo = ($this->k03_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k03_tipo"]:$this->k03_tipo);
       $this->k00_marcado = ($this->k00_marcado == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_marcado"]:$this->k00_marcado);
       $this->k00_hist1 = ($this->k00_hist1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist1"]:$this->k00_hist1);
       $this->k00_hist2 = ($this->k00_hist2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist2"]:$this->k00_hist2);
       $this->k00_hist3 = ($this->k00_hist3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist3"]:$this->k00_hist3);
       $this->k00_hist4 = ($this->k00_hist4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist4"]:$this->k00_hist4);
       $this->k00_hist5 = ($this->k00_hist5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist5"]:$this->k00_hist5);
       $this->k00_hist6 = ($this->k00_hist6 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist6"]:$this->k00_hist6);
       $this->k00_hist7 = ($this->k00_hist7 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist7"]:$this->k00_hist7);
       $this->k00_hist8 = ($this->k00_hist8 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist8"]:$this->k00_hist8);
       $this->k00_tipoagrup = ($this->k00_tipoagrup == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipoagrup"]:$this->k00_tipoagrup);
       $this->k00_recibodbpref = ($this->k00_recibodbpref == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_recibodbpref"]:$this->k00_recibodbpref);
       $this->k00_instit = ($this->k00_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_instit"]:$this->k00_instit);
       $this->k00_formemissao = ($this->k00_formemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_formemissao"]:$this->k00_formemissao);
       $this->k00_receitacredito = ($this->k00_receitacredito == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receitacredito"]:$this->k00_receitacredito);
       $this->k00_exercicioscarne = ($this->k00_exercicioscarne == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_exercicioscarne"]:$this->k00_exercicioscarne);
     }else{
       $this->k00_tipo = ($this->k00_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipo"]:$this->k00_tipo);
     }
   }
   // funcao para inclusao
   function incluir ($k00_tipo){
      $this->atualizacampos();
     if($this->k00_codbco == null ){
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "k00_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_codage == null ){
       $this->erro_sql = " Campo codigo da agencia nao Informado.";
       $this->erro_campo = "k00_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_descr == null ){
       $this->erro_sql = " Campo descricao do tipo de debito nao Informado.";
       $this->erro_campo = "k00_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_emrec == null ){
       $this->erro_sql = " Campo emite recibo nao Informado.";
       $this->erro_campo = "k00_emrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_agnum == null ){
       $this->erro_sql = " Campo agrupa pelo numpre nao Informado.";
       $this->erro_campo = "k00_agnum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_agpar == null ){
       $this->erro_sql = " Campo agrupa pelo numpar nao Informado.";
       $this->erro_campo = "k00_agpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tercdigcarneunica == null ){
       $this->erro_sql = " Campo Terc. digito cod. barras carne única nao Informado.";
       $this->erro_campo = "k00_tercdigcarneunica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tercdigcarnenormal == null ){
       $this->erro_sql = " Campo Terc. digito cod. barras carne normal nao Informado.";
       $this->erro_campo = "k00_tercdigcarnenormal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tercdigrecunica == null ){
       $this->erro_sql = " Campo Terc. digito cod. barras recibo unica nao Informado.";
       $this->erro_campo = "k00_tercdigrecunica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tercdigrecnormal == null ){
       $this->erro_sql = " Campo Terc. digito cod. barras recibo normal nao Informado.";
       $this->erro_campo = "k00_tercdigrecnormal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_txban == null ){
       $this->k00_txban = "0";
     }
     if($this->k00_rectx == null ){
       $this->k00_rectx = "0";
     }
     if($this->codmodelo == null ){
       $this->erro_sql = " Campo Código Modelo nao Informado.";
       $this->erro_campo = "codmodelo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_impval == null ){
       $this->erro_sql = " Campo Imprime valor nao Informado.";
       $this->erro_campo = "k00_impval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrmin == null ){
       $this->k00_vlrmin = "0";
     }
     if($this->k03_tipo == null ){
       $this->erro_sql = " Campo Grupo de  Débito nao Informado.";
       $this->erro_campo = "k03_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_marcado == null ){
       $this->erro_sql = " Campo Marcado nao Informado.";
       $this->erro_campo = "k00_marcado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tipoagrup == null ){
       $this->erro_sql = " Campo Tipo de agrupamento nao Informado.";
       $this->erro_campo = "k00_tipoagrup";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_recibodbpref == null ){
       $this->erro_sql = " Campo Libera recibo dbpref nao Informado.";
       $this->erro_campo = "k00_recibodbpref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k00_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_formemissao == null ){
       $this->erro_sql = " Campo Forma Emissão 2a. Via de Carnês nao Informado.";
       $this->erro_campo = "k00_formemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_receitacredito == null ){
       $this->erro_sql = " Campo Receita de Crédito nao Informado.";
       $this->erro_campo = "k00_receitacredito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_exercicioscarne == null ){
       $this->k00_exercicioscarne = "0";
     }
     if($k00_tipo == "" || $k00_tipo == null ){
       $result = db_query("select nextval('arretipo_k00_tipo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arretipo_k00_tipo_seq do campo: k00_tipo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k00_tipo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from arretipo_k00_tipo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_tipo)){
         $this->erro_sql = " Campo k00_tipo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_tipo = $k00_tipo;
       }
     }
     if(($this->k00_tipo == null) || ($this->k00_tipo == "") ){
       $this->erro_sql = " Campo k00_tipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arretipo(
                                       k00_codbco
                                      ,k00_codage
                                      ,k00_tipo
                                      ,k00_descr
                                      ,k00_emrec
                                      ,k00_agnum
                                      ,k00_agpar
                                      ,k00_msguni
                                      ,k00_msguni2
                                      ,k00_msgparc
                                      ,k00_msgparc2
                                      ,k00_msgparcvenc
                                      ,k00_msgparcvenc2
                                      ,k00_msgrecibo
                                      ,k00_tercdigcarneunica
                                      ,k00_tercdigcarnenormal
                                      ,k00_tercdigrecunica
                                      ,k00_tercdigrecnormal
                                      ,k00_txban
                                      ,k00_rectx
                                      ,codmodelo
                                      ,k00_impval
                                      ,k00_vlrmin
                                      ,k03_tipo
                                      ,k00_marcado
                                      ,k00_hist1
                                      ,k00_hist2
                                      ,k00_hist3
                                      ,k00_hist4
                                      ,k00_hist5
                                      ,k00_hist6
                                      ,k00_hist7
                                      ,k00_hist8
                                      ,k00_tipoagrup
                                      ,k00_recibodbpref
                                      ,k00_instit
                                      ,k00_formemissao
                                      ,k00_receitacredito
                                      ,k00_exercicioscarne
                       )
                values (
                                $this->k00_codbco
                               ,'$this->k00_codage'
                               ,$this->k00_tipo
                               ,'$this->k00_descr'
                               ,'$this->k00_emrec'
                               ,'$this->k00_agnum'
                               ,'$this->k00_agpar'
                               ,'$this->k00_msguni'
                               ,'$this->k00_msguni2'
                               ,'$this->k00_msgparc'
                               ,'$this->k00_msgparc2'
                               ,'$this->k00_msgparcvenc'
                               ,'$this->k00_msgparcvenc2'
                               ,'$this->k00_msgrecibo'
                               ,$this->k00_tercdigcarneunica
                               ,$this->k00_tercdigcarnenormal
                               ,$this->k00_tercdigrecunica
                               ,$this->k00_tercdigrecnormal
                               ,$this->k00_txban
                               ,$this->k00_rectx
                               ,$this->codmodelo
                               ,'$this->k00_impval'
                               ,$this->k00_vlrmin
                               ,$this->k03_tipo
                               ,'$this->k00_marcado'
                               ,'$this->k00_hist1'
                               ,'$this->k00_hist2'
                               ,'$this->k00_hist3'
                               ,'$this->k00_hist4'
                               ,'$this->k00_hist5'
                               ,'$this->k00_hist6'
                               ,'$this->k00_hist7'
                               ,'$this->k00_hist8'
                               ,$this->k00_tipoagrup
                               ,$this->k00_recibodbpref
                               ,$this->k00_instit
                               ,$this->k00_formemissao
                               ,$this->k00_receitacredito
                               ,$this->k00_exercicioscarne
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k00_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k00_tipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_tipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_tipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,380,'$this->k00_tipo','I')");
       $resac = db_query("insert into db_acount values($acount,82,363,'','".AddSlashes(pg_result($resaco,0,'k00_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,364,'','".AddSlashes(pg_result($resaco,0,'k00_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,380,'','".AddSlashes(pg_result($resaco,0,'k00_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,473,'','".AddSlashes(pg_result($resaco,0,'k00_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,474,'','".AddSlashes(pg_result($resaco,0,'k00_emrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,475,'','".AddSlashes(pg_result($resaco,0,'k00_agnum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,476,'','".AddSlashes(pg_result($resaco,0,'k00_agpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8575,'','".AddSlashes(pg_result($resaco,0,'k00_msguni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8578,'','".AddSlashes(pg_result($resaco,0,'k00_msguni2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8576,'','".AddSlashes(pg_result($resaco,0,'k00_msgparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8577,'','".AddSlashes(pg_result($resaco,0,'k00_msgparc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8606,'','".AddSlashes(pg_result($resaco,0,'k00_msgparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8607,'','".AddSlashes(pg_result($resaco,0,'k00_msgparcvenc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8579,'','".AddSlashes(pg_result($resaco,0,'k00_msgrecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8854,'','".AddSlashes(pg_result($resaco,0,'k00_tercdigcarneunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8853,'','".AddSlashes(pg_result($resaco,0,'k00_tercdigcarnenormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8852,'','".AddSlashes(pg_result($resaco,0,'k00_tercdigrecunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8851,'','".AddSlashes(pg_result($resaco,0,'k00_tercdigrecnormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,1206,'','".AddSlashes(pg_result($resaco,0,'k00_txban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,1207,'','".AddSlashes(pg_result($resaco,0,'k00_rectx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,1153,'','".AddSlashes(pg_result($resaco,0,'codmodelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,2426,'','".AddSlashes(pg_result($resaco,0,'k00_impval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,7381,'','".AddSlashes(pg_result($resaco,0,'k00_vlrmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,2482,'','".AddSlashes(pg_result($resaco,0,'k03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8234,'','".AddSlashes(pg_result($resaco,0,'k00_marcado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,477,'','".AddSlashes(pg_result($resaco,0,'k00_hist1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,478,'','".AddSlashes(pg_result($resaco,0,'k00_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,479,'','".AddSlashes(pg_result($resaco,0,'k00_hist3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,484,'','".AddSlashes(pg_result($resaco,0,'k00_hist4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,480,'','".AddSlashes(pg_result($resaco,0,'k00_hist5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,481,'','".AddSlashes(pg_result($resaco,0,'k00_hist6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,482,'','".AddSlashes(pg_result($resaco,0,'k00_hist7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,483,'','".AddSlashes(pg_result($resaco,0,'k00_hist8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,8627,'','".AddSlashes(pg_result($resaco,0,'k00_tipoagrup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,10286,'','".AddSlashes(pg_result($resaco,0,'k00_recibodbpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,10555,'','".AddSlashes(pg_result($resaco,0,'k00_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,17508,'','".AddSlashes(pg_result($resaco,0,'k00_formemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,18078,'','".AddSlashes(pg_result($resaco,0,'k00_receitacredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,82,19739,'','".AddSlashes(pg_result($resaco,0,'k00_exercicioscarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k00_tipo=null) {
      $this->atualizacampos();
     $sql = " update arretipo set ";
     $virgula = "";
     if(trim($this->k00_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codbco"])){
       $sql  .= $virgula." k00_codbco = $this->k00_codbco ";
       $virgula = ",";
       if(trim($this->k00_codbco) == null ){
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "k00_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codage"])){
       $sql  .= $virgula." k00_codage = '$this->k00_codage' ";
       $virgula = ",";
       if(trim($this->k00_codage) == null ){
         $this->erro_sql = " Campo codigo da agencia nao Informado.";
         $this->erro_campo = "k00_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"])){
       $sql  .= $virgula." k00_tipo = $this->k00_tipo ";
       $virgula = ",";
       if(trim($this->k00_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "k00_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_descr"])){
       $sql  .= $virgula." k00_descr = '$this->k00_descr' ";
       $virgula = ",";
       if(trim($this->k00_descr) == null ){
         $this->erro_sql = " Campo descricao do tipo de debito nao Informado.";
         $this->erro_campo = "k00_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_emrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_emrec"])){
       $sql  .= $virgula." k00_emrec = '$this->k00_emrec' ";
       $virgula = ",";
       if(trim($this->k00_emrec) == null ){
         $this->erro_sql = " Campo emite recibo nao Informado.";
         $this->erro_campo = "k00_emrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_agnum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_agnum"])){
       $sql  .= $virgula." k00_agnum = '$this->k00_agnum' ";
       $virgula = ",";
       if(trim($this->k00_agnum) == null ){
         $this->erro_sql = " Campo agrupa pelo numpre nao Informado.";
         $this->erro_campo = "k00_agnum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_agpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_agpar"])){
       $sql  .= $virgula." k00_agpar = '$this->k00_agpar' ";
       $virgula = ",";
       if(trim($this->k00_agpar) == null ){
         $this->erro_sql = " Campo agrupa pelo numpar nao Informado.";
         $this->erro_campo = "k00_agpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_msguni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msguni"])){
       $sql  .= $virgula." k00_msguni = '$this->k00_msguni' ";
       $virgula = ",";
     }
     if(trim($this->k00_msguni2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msguni2"])){
       $sql  .= $virgula." k00_msguni2 = '$this->k00_msguni2' ";
       $virgula = ",";
     }
     if(trim($this->k00_msgparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparc"])){
       $sql  .= $virgula." k00_msgparc = '$this->k00_msgparc' ";
       $virgula = ",";
     }
     if(trim($this->k00_msgparc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparc2"])){
       $sql  .= $virgula." k00_msgparc2 = '$this->k00_msgparc2' ";
       $virgula = ",";
     }
     if(trim($this->k00_msgparcvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc"])){
       $sql  .= $virgula." k00_msgparcvenc = '$this->k00_msgparcvenc' ";
       $virgula = ",";
     }
     if(trim($this->k00_msgparcvenc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc2"])){
       $sql  .= $virgula." k00_msgparcvenc2 = '$this->k00_msgparcvenc2' ";
       $virgula = ",";
     }
     if(trim($this->k00_msgrecibo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_msgrecibo"])){
       $sql  .= $virgula." k00_msgrecibo = '$this->k00_msgrecibo' ";
       $virgula = ",";
     }
     if(trim($this->k00_tercdigcarneunica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarneunica"])){
       $sql  .= $virgula." k00_tercdigcarneunica = $this->k00_tercdigcarneunica ";
       $virgula = ",";
       if(trim($this->k00_tercdigcarneunica) == null ){
         $this->erro_sql = " Campo Terc. digito cod. barras carne única nao Informado.";
         $this->erro_campo = "k00_tercdigcarneunica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tercdigcarnenormal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarnenormal"])){
       $sql  .= $virgula." k00_tercdigcarnenormal = $this->k00_tercdigcarnenormal ";
       $virgula = ",";
       if(trim($this->k00_tercdigcarnenormal) == null ){
         $this->erro_sql = " Campo Terc. digito cod. barras carne normal nao Informado.";
         $this->erro_campo = "k00_tercdigcarnenormal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tercdigrecunica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecunica"])){
       $sql  .= $virgula." k00_tercdigrecunica = $this->k00_tercdigrecunica ";
       $virgula = ",";
       if(trim($this->k00_tercdigrecunica) == null ){
         $this->erro_sql = " Campo Terc. digito cod. barras recibo unica nao Informado.";
         $this->erro_campo = "k00_tercdigrecunica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tercdigrecnormal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecnormal"])){
       $sql  .= $virgula." k00_tercdigrecnormal = $this->k00_tercdigrecnormal ";
       $virgula = ",";
       if(trim($this->k00_tercdigrecnormal) == null ){
         $this->erro_sql = " Campo Terc. digito cod. barras recibo normal nao Informado.";
         $this->erro_campo = "k00_tercdigrecnormal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_txban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_txban"])){
        if(trim($this->k00_txban)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_txban"])){
           $this->k00_txban = "0" ;
        }
       $sql  .= $virgula." k00_txban = $this->k00_txban ";
       $virgula = ",";
     }
     if(trim($this->k00_rectx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_rectx"])){
        if(trim($this->k00_rectx)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_rectx"])){
           $this->k00_rectx = "0" ;
        }
       $sql  .= $virgula." k00_rectx = $this->k00_rectx ";
       $virgula = ",";
     }
     if(trim($this->codmodelo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codmodelo"])){
       $sql  .= $virgula." codmodelo = $this->codmodelo ";
       $virgula = ",";
       if(trim($this->codmodelo) == null ){
         $this->erro_sql = " Campo Código Modelo nao Informado.";
         $this->erro_campo = "codmodelo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_impval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_impval"])){
       $sql  .= $virgula." k00_impval = '$this->k00_impval' ";
       $virgula = ",";
       if(trim($this->k00_impval) == null ){
         $this->erro_sql = " Campo Imprime valor nao Informado.";
         $this->erro_campo = "k00_impval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_vlrmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmin"])){
        if(trim($this->k00_vlrmin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmin"])){
           $this->k00_vlrmin = "0" ;
        }
       $sql  .= $virgula." k00_vlrmin = $this->k00_vlrmin ";
       $virgula = ",";
     }
     if(trim($this->k03_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k03_tipo"])){
       $sql  .= $virgula." k03_tipo = $this->k03_tipo ";
       $virgula = ",";
       if(trim($this->k03_tipo) == null ){
         $this->erro_sql = " Campo Grupo de  Débito nao Informado.";
         $this->erro_campo = "k03_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_marcado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_marcado"])){
       $sql  .= $virgula." k00_marcado = '$this->k00_marcado' ";
       $virgula = ",";
       if(trim($this->k00_marcado) == null ){
         $this->erro_sql = " Campo Marcado nao Informado.";
         $this->erro_campo = "k00_marcado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist1"])){
       $sql  .= $virgula." k00_hist1 = '$this->k00_hist1' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist2"])){
       $sql  .= $virgula." k00_hist2 = '$this->k00_hist2' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist3"])){
       $sql  .= $virgula." k00_hist3 = '$this->k00_hist3' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist4"])){
       $sql  .= $virgula." k00_hist4 = '$this->k00_hist4' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist5"])){
       $sql  .= $virgula." k00_hist5 = '$this->k00_hist5' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist6"])){
       $sql  .= $virgula." k00_hist6 = '$this->k00_hist6' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist7"])){
       $sql  .= $virgula." k00_hist7 = '$this->k00_hist7' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist8"])){
       $sql  .= $virgula." k00_hist8 = '$this->k00_hist8' ";
       $virgula = ",";
     }
     if(trim($this->k00_tipoagrup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipoagrup"])){
       $sql  .= $virgula." k00_tipoagrup = $this->k00_tipoagrup ";
       $virgula = ",";
       if(trim($this->k00_tipoagrup) == null ){
         $this->erro_sql = " Campo Tipo de agrupamento nao Informado.";
         $this->erro_campo = "k00_tipoagrup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_recibodbpref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_recibodbpref"])){
       $sql  .= $virgula." k00_recibodbpref = $this->k00_recibodbpref ";
       $virgula = ",";
       if(trim($this->k00_recibodbpref) == null ){
         $this->erro_sql = " Campo Libera recibo dbpref nao Informado.";
         $this->erro_campo = "k00_recibodbpref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_instit"])){
       $sql  .= $virgula." k00_instit = $this->k00_instit ";
       $virgula = ",";
       if(trim($this->k00_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k00_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_formemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_formemissao"])){
       $sql  .= $virgula." k00_formemissao = $this->k00_formemissao ";
       $virgula = ",";
       if(trim($this->k00_formemissao) == null ){
         $this->erro_sql = " Campo Forma Emissão 2a. Via de Carnês nao Informado.";
         $this->erro_campo = "k00_formemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_receitacredito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receitacredito"])){
       $sql  .= $virgula." k00_receitacredito = $this->k00_receitacredito ";
       $virgula = ",";
       if(trim($this->k00_receitacredito) == null ){
         $this->erro_sql = " Campo Receita de Crédito nao Informado.";
         $this->erro_campo = "k00_receitacredito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_exercicioscarne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_exercicioscarne"])){
        if(trim($this->k00_exercicioscarne)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_exercicioscarne"])){
           $this->k00_exercicioscarne = "0" ;
        }
       $sql  .= $virgula." k00_exercicioscarne = $this->k00_exercicioscarne ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k00_tipo!=null){
       $sql .= " k00_tipo = $this->k00_tipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_tipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,380,'$this->k00_tipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_codbco"]) || $this->k00_codbco != "")
           $resac = db_query("insert into db_acount values($acount,82,363,'".AddSlashes(pg_result($resaco,$conresaco,'k00_codbco'))."','$this->k00_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_codage"]) || $this->k00_codage != "")
           $resac = db_query("insert into db_acount values($acount,82,364,'".AddSlashes(pg_result($resaco,$conresaco,'k00_codage'))."','$this->k00_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"]) || $this->k00_tipo != "")
           $resac = db_query("insert into db_acount values($acount,82,380,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tipo'))."','$this->k00_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_descr"]) || $this->k00_descr != "")
           $resac = db_query("insert into db_acount values($acount,82,473,'".AddSlashes(pg_result($resaco,$conresaco,'k00_descr'))."','$this->k00_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_emrec"]) || $this->k00_emrec != "")
           $resac = db_query("insert into db_acount values($acount,82,474,'".AddSlashes(pg_result($resaco,$conresaco,'k00_emrec'))."','$this->k00_emrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_agnum"]) || $this->k00_agnum != "")
           $resac = db_query("insert into db_acount values($acount,82,475,'".AddSlashes(pg_result($resaco,$conresaco,'k00_agnum'))."','$this->k00_agnum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_agpar"]) || $this->k00_agpar != "")
           $resac = db_query("insert into db_acount values($acount,82,476,'".AddSlashes(pg_result($resaco,$conresaco,'k00_agpar'))."','$this->k00_agpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msguni"]) || $this->k00_msguni != "")
           $resac = db_query("insert into db_acount values($acount,82,8575,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msguni'))."','$this->k00_msguni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msguni2"]) || $this->k00_msguni2 != "")
           $resac = db_query("insert into db_acount values($acount,82,8578,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msguni2'))."','$this->k00_msguni2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparc"]) || $this->k00_msgparc != "")
           $resac = db_query("insert into db_acount values($acount,82,8576,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msgparc'))."','$this->k00_msgparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparc2"]) || $this->k00_msgparc2 != "")
           $resac = db_query("insert into db_acount values($acount,82,8577,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msgparc2'))."','$this->k00_msgparc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc"]) || $this->k00_msgparcvenc != "")
           $resac = db_query("insert into db_acount values($acount,82,8606,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msgparcvenc'))."','$this->k00_msgparcvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msgparcvenc2"]) || $this->k00_msgparcvenc2 != "")
           $resac = db_query("insert into db_acount values($acount,82,8607,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msgparcvenc2'))."','$this->k00_msgparcvenc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_msgrecibo"]) || $this->k00_msgrecibo != "")
           $resac = db_query("insert into db_acount values($acount,82,8579,'".AddSlashes(pg_result($resaco,$conresaco,'k00_msgrecibo'))."','$this->k00_msgrecibo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarneunica"]) || $this->k00_tercdigcarneunica != "")
           $resac = db_query("insert into db_acount values($acount,82,8854,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tercdigcarneunica'))."','$this->k00_tercdigcarneunica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigcarnenormal"]) || $this->k00_tercdigcarnenormal != "")
           $resac = db_query("insert into db_acount values($acount,82,8853,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tercdigcarnenormal'))."','$this->k00_tercdigcarnenormal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecunica"]) || $this->k00_tercdigrecunica != "")
           $resac = db_query("insert into db_acount values($acount,82,8852,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tercdigrecunica'))."','$this->k00_tercdigrecunica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tercdigrecnormal"]) || $this->k00_tercdigrecnormal != "")
           $resac = db_query("insert into db_acount values($acount,82,8851,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tercdigrecnormal'))."','$this->k00_tercdigrecnormal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_txban"]) || $this->k00_txban != "")
           $resac = db_query("insert into db_acount values($acount,82,1206,'".AddSlashes(pg_result($resaco,$conresaco,'k00_txban'))."','$this->k00_txban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_rectx"]) || $this->k00_rectx != "")
           $resac = db_query("insert into db_acount values($acount,82,1207,'".AddSlashes(pg_result($resaco,$conresaco,'k00_rectx'))."','$this->k00_rectx',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codmodelo"]) || $this->codmodelo != "")
           $resac = db_query("insert into db_acount values($acount,82,1153,'".AddSlashes(pg_result($resaco,$conresaco,'codmodelo'))."','$this->codmodelo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_impval"]) || $this->k00_impval != "")
           $resac = db_query("insert into db_acount values($acount,82,2426,'".AddSlashes(pg_result($resaco,$conresaco,'k00_impval'))."','$this->k00_impval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmin"]) || $this->k00_vlrmin != "")
           $resac = db_query("insert into db_acount values($acount,82,7381,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrmin'))."','$this->k00_vlrmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k03_tipo"]) || $this->k03_tipo != "")
           $resac = db_query("insert into db_acount values($acount,82,2482,'".AddSlashes(pg_result($resaco,$conresaco,'k03_tipo'))."','$this->k03_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_marcado"]) || $this->k00_marcado != "")
           $resac = db_query("insert into db_acount values($acount,82,8234,'".AddSlashes(pg_result($resaco,$conresaco,'k00_marcado'))."','$this->k00_marcado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist1"]) || $this->k00_hist1 != "")
           $resac = db_query("insert into db_acount values($acount,82,477,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist1'))."','$this->k00_hist1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist2"]) || $this->k00_hist2 != "")
           $resac = db_query("insert into db_acount values($acount,82,478,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist2'))."','$this->k00_hist2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist3"]) || $this->k00_hist3 != "")
           $resac = db_query("insert into db_acount values($acount,82,479,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist3'))."','$this->k00_hist3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist4"]) || $this->k00_hist4 != "")
           $resac = db_query("insert into db_acount values($acount,82,484,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist4'))."','$this->k00_hist4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist5"]) || $this->k00_hist5 != "")
           $resac = db_query("insert into db_acount values($acount,82,480,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist5'))."','$this->k00_hist5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist6"]) || $this->k00_hist6 != "")
           $resac = db_query("insert into db_acount values($acount,82,481,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist6'))."','$this->k00_hist6',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist7"]) || $this->k00_hist7 != "")
           $resac = db_query("insert into db_acount values($acount,82,482,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist7'))."','$this->k00_hist7',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist8"]) || $this->k00_hist8 != "")
           $resac = db_query("insert into db_acount values($acount,82,483,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist8'))."','$this->k00_hist8',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tipoagrup"]) || $this->k00_tipoagrup != "")
           $resac = db_query("insert into db_acount values($acount,82,8627,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tipoagrup'))."','$this->k00_tipoagrup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_recibodbpref"]) || $this->k00_recibodbpref != "")
           $resac = db_query("insert into db_acount values($acount,82,10286,'".AddSlashes(pg_result($resaco,$conresaco,'k00_recibodbpref'))."','$this->k00_recibodbpref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_instit"]) || $this->k00_instit != "")
           $resac = db_query("insert into db_acount values($acount,82,10555,'".AddSlashes(pg_result($resaco,$conresaco,'k00_instit'))."','$this->k00_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_formemissao"]) || $this->k00_formemissao != "")
           $resac = db_query("insert into db_acount values($acount,82,17508,'".AddSlashes(pg_result($resaco,$conresaco,'k00_formemissao'))."','$this->k00_formemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_receitacredito"]) || $this->k00_receitacredito != "")
           $resac = db_query("insert into db_acount values($acount,82,18078,'".AddSlashes(pg_result($resaco,$conresaco,'k00_receitacredito'))."','$this->k00_receitacredito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_exercicioscarne"]) || $this->k00_exercicioscarne != "")
           $resac = db_query("insert into db_acount values($acount,82,19739,'".AddSlashes(pg_result($resaco,$conresaco,'k00_exercicioscarne'))."','$this->k00_exercicioscarne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k00_tipo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_tipo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,380,'$k00_tipo','E')");
         $resac = db_query("insert into db_acount values($acount,82,363,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,364,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,380,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,473,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,474,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_emrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,475,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_agnum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,476,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_agpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8575,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msguni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8578,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msguni2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8576,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msgparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8577,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msgparc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8606,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msgparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8607,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msgparcvenc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8579,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_msgrecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8854,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tercdigcarneunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8853,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tercdigcarnenormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8852,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tercdigrecunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8851,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tercdigrecnormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,1206,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_txban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,1207,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_rectx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,1153,'','".AddSlashes(pg_result($resaco,$iresaco,'codmodelo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,2426,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_impval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,7381,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,2482,'','".AddSlashes(pg_result($resaco,$iresaco,'k03_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8234,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_marcado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,477,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,478,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,479,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,484,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,480,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,481,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist6'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,482,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist7'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,483,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist8'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,8627,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tipoagrup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,10286,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_recibodbpref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,10555,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,17508,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_formemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,18078,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_receitacredito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,82,19739,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_exercicioscarne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arretipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_tipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_tipo = $k00_tipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_tipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_tipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:arretipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k00_tipo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arretipo ";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      left  join tabrec     on  tabrec.k02_codigo = arretipo.k00_receitacredito ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_tipo!=null ){
         $sql2 .= " where arretipo.k00_tipo = $k00_tipo ";
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
   function sql_query_file ( $k00_tipo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arretipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_tipo!=null ){
         $sql2 .= " where arretipo.k00_tipo = $k00_tipo ";
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
   function sql_query_numpre ($numpre,$campos="*") {
   $sql =  "select  $campos
                 from arretipo
       inner join arrecad on arretipo.k00_tipo = arrecad.k00_tipo
                where k00_numpre = $numpre";
  $result = @db_query($sql);
  if($result==false){
    $this->numrows = 0;
  }else{
    $this->numrows = pg_numrows($result);
  }
  return $result;
}
   function sql_query_tiposDebitosByMatricula ( $iMatricula, $iInstituicao, $sCampos = "*") {

     $sql = "select $sCampos                                                                      \n";
     $sql.= "  from arrematric                                                                    \n";
     $sql.= "       inner join arrecad     on arrecad.k00_numpre     = arrematric.k00_numpre      \n";
     $sql.= "       inner join arreinstit  on arreinstit.k00_numpre  = arrecad.k00_numpre         \n";
     $sql.= "                             and arreinstit.k00_instit  = {$iInstituicao}            \n";
     $sql.= "       inner join iptubase    on iptubase.j01_matric    = arrematric.k00_matric      \n";
     $sql.= "       inner join arretipo    on arretipo.k00_tipo      = arrecad.k00_tipo           \n";
     $sql.= " where arretipo.k00_instit = {$iInstituicao}                                         \n";
     $sql.= "   and arrematric.k00_matric = {$iMatricula}                                         \n";
     return $sql;
   }

   function sql_query_tiposDebitosByInscricao ( $iInscricao, $iInstituicao, $sCampos = "*") {

     $sql = " select $sCampos                                                                     \n";
     $sql.= "   from arreinscr                                                                    \n";
     $sql.= "               inner join arrecad     on arreinscr.k00_numpre  = arrecad.k00_numpre  \n";
     $sql.= "               inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre  \n";
     $sql.= "                                     and arreinstit.k00_instit = {$iInstituicao}     \n";
     $sql.= "               inner join issbase     on issbase.q02_inscr     = arreinscr.k00_inscr \n";
     $sql.= "               inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo    \n";
     $sql.= "  where arreinscr.k00_inscr = {$iInscricao}                                          \n";
     return $sql;
   }

   function sql_query_tiposDebitosByCgm       ( $iNumCgm,    $iInstituicao, $sCampos = "*") {

     $sql = " select $sCampos                                                                     \n";
     $sql.= "     from arrenumcgm                                                                 \n";
     $sql.= "               inner join arrecad     on arrenumcgm.k00_numpre = arrecad.k00_numpre  \n";
     $sql.= "               inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre  \n";
     $sql.= "                                     and arreinstit.k00_instit = {$iInstituicao}     \n";
     $sql.= "               inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo    \n";
     $sql.= "         where arrenumcgm.k00_numcgm = {$iNumCgm}                                    \n";
     return $sql;
   }

   function sql_query_tiposDebitosByNumpre    ( $iNumpre,    $iInstituicao, $sCampos = "*") {

     $sql = " select $sCampos                                                                     \n";
     $sql.= "     from arrenumcgm                                                                 \n";
     $sql.= "               inner join arrecad     on arrenumcgm.k00_numpre = arrecad.k00_numpre  \n";
     $sql.= "               inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre  \n";
     $sql.= "                                     and arreinstit.k00_instit = {$iInstituicao}     \n";
     $sql.= "               inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo    \n";
     $sql.= "         where arrenumcgm.k00_numpre = {$iNumpre}                                    \n";
     return $sql;
   }
}