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

//MODULO: pessoal
//CLASSE DA ENTIDADE folha
class cl_folha {
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
   var $r38_regist = 0;
   var $r38_nome = null;
   var $r38_numcgm = 0;
   var $r38_regime = 0;
   var $r38_lotac = null;
   var $r38_vincul = null;
   var $r38_padrao = null;
   var $r38_salari = 0;
   var $r38_funcao = null;
   var $r38_banco = null;
   var $r38_agenc = null;
   var $r38_conta = null;
   var $r38_situac = 0;
   var $r38_previd = 0;
   var $r38_liq = 0;
   var $r38_prov = 0;
   var $r38_desc = 0;
   var $r38_proc_dia = null;
   var $r38_proc_mes = null;
   var $r38_proc_ano = null;
   var $r38_proc = null;
   var $r38_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 r38_regist = int4 = Codigo do Funcionario
                 r38_nome = varchar(40) = Nome
                 r38_numcgm = int4 = Numero CGM
                 r38_regime = int4 = Codigo do Regime do Func.
                 r38_lotac = varchar(4) = Lotação
                 r38_vincul = varchar(1) = Vinculo
                 r38_padrao = varchar(10) = Padrão
                 r38_salari = float8 = Salario do Funcionario
                 r38_funcao = varchar(30) = Função
                 r38_banco = varchar(3) = Banco
                 r38_agenc = varchar(7) = Agência
                 r38_conta = varchar(15) = Conta Corrente
                 r38_situac = int4 = Situacao do funcionario
                 r38_previd = int4 = Tabela da previdencia
                 r38_liq = float8 = Salario Liquido
                 r38_prov = float8 = Proventos do funcionario
                 r38_desc = float8 = Descontos do funcionario
                 r38_proc = date = Processado
                 r38_instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_folha() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("folha");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if( ($this->erro_status == "0") || ($mostra == true && $this->erro_status != null ) ){

        db_msgbox($this->erro_msg);

        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r38_regist = ($this->r38_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_regist"]:$this->r38_regist);
       $this->r38_nome = ($this->r38_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_nome"]:$this->r38_nome);
       $this->r38_numcgm = ($this->r38_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_numcgm"]:$this->r38_numcgm);
       $this->r38_regime = ($this->r38_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_regime"]:$this->r38_regime);
       $this->r38_lotac = ($this->r38_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_lotac"]:$this->r38_lotac);
       $this->r38_vincul = ($this->r38_vincul == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_vincul"]:$this->r38_vincul);
       $this->r38_padrao = ($this->r38_padrao == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_padrao"]:$this->r38_padrao);
       $this->r38_salari = ($this->r38_salari == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_salari"]:$this->r38_salari);
       $this->r38_funcao = ($this->r38_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_funcao"]:$this->r38_funcao);
       $this->r38_banco = ($this->r38_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_banco"]:$this->r38_banco);
       $this->r38_agenc = ($this->r38_agenc == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_agenc"]:$this->r38_agenc);
       $this->r38_conta = ($this->r38_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_conta"]:$this->r38_conta);
       $this->r38_situac = ($this->r38_situac == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_situac"]:$this->r38_situac);
       $this->r38_previd = ($this->r38_previd == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_previd"]:$this->r38_previd);
       $this->r38_liq = ($this->r38_liq == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_liq"]:$this->r38_liq);
       $this->r38_prov = ($this->r38_prov == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_prov"]:$this->r38_prov);
       $this->r38_desc = ($this->r38_desc == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_desc"]:$this->r38_desc);
       if($this->r38_proc == ""){
         $this->r38_proc_dia = ($this->r38_proc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_proc_dia"]:$this->r38_proc_dia);
         $this->r38_proc_mes = ($this->r38_proc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_proc_mes"]:$this->r38_proc_mes);
         $this->r38_proc_ano = ($this->r38_proc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_proc_ano"]:$this->r38_proc_ano);
         if($this->r38_proc_dia != ""){
            $this->r38_proc = $this->r38_proc_ano."-".$this->r38_proc_mes."-".$this->r38_proc_dia;
         }
       }
       $this->r38_instit = ($this->r38_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_instit"]:$this->r38_instit);
     }else{
       $this->r38_regist = ($this->r38_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_regist"]:$this->r38_regist);
       $this->r38_instit = ($this->r38_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r38_instit"]:$this->r38_instit);
     }
   }
   // funcao para inclusao
   function incluir ($r38_regist,$r38_instit){
      $this->atualizacampos();
     if($this->r38_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "r38_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r38_numcgm == null ){
       $this->erro_sql = " Campo Numero CGM nao Informado.";
       $this->erro_campo = "r38_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r38_regime == null ){
       $this->r38_regime = "0";
     }
     if($this->r38_salari == null ){
       $this->r38_salari = "0";
     }
     if($this->r38_situac == null ){
       $this->r38_situac = "0";
     }
     if($this->r38_previd == null ){
       $this->r38_previd = "0";
     }
     if($this->r38_liq == null ){
       $this->erro_sql = " Campo Salario Liquido nao Informado.";
       $this->erro_campo = "r38_liq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r38_prov == null ){
       $this->erro_sql = " Campo Proventos do funcionario nao Informado.";
       $this->erro_campo = "r38_prov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r38_desc == null ){
       $this->erro_sql = " Campo Descontos do funcionario nao Informado.";
       $this->erro_campo = "r38_desc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r38_proc == null ){
       $this->r38_proc = "null";
     }
       $this->r38_regist = $r38_regist;
       $this->r38_instit = $r38_instit;
     if(($this->r38_regist == null) || ($this->r38_regist == "") ){
       $this->erro_sql = " Campo r38_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r38_instit == null) || ($this->r38_instit == "") ){
       $this->erro_sql = " Campo r38_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into folha(
                                       r38_regist
                                      ,r38_nome
                                      ,r38_numcgm
                                      ,r38_regime
                                      ,r38_lotac
                                      ,r38_vincul
                                      ,r38_padrao
                                      ,r38_salari
                                      ,r38_funcao
                                      ,r38_banco
                                      ,r38_agenc
                                      ,r38_conta
                                      ,r38_situac
                                      ,r38_previd
                                      ,r38_liq
                                      ,r38_prov
                                      ,r38_desc
                                      ,r38_proc
                                      ,r38_instit
                       )
                values (
                                $this->r38_regist
                               ,'$this->r38_nome'
                               ,$this->r38_numcgm
                               ,$this->r38_regime
                               ,'$this->r38_lotac'
                               ,'$this->r38_vincul'
                               ,'$this->r38_padrao'
                               ,$this->r38_salari
                               ,'$this->r38_funcao'
                               ,'$this->r38_banco'
                               ,'$this->r38_agenc'
                               ,'$this->r38_conta'
                               ,$this->r38_situac
                               ,$this->r38_previd
                               ,$this->r38_liq
                               ,$this->r38_prov
                               ,$this->r38_desc
                               ,".($this->r38_proc == "null" || $this->r38_proc == ""?"null":"'".$this->r38_proc."'")."
                               ,$this->r38_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Auxiliar de Cálculos ($this->r38_regist."-".$this->r38_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Auxiliar de Cálculos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Auxiliar de Cálculos ($this->r38_regist."-".$this->r38_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r38_regist."-".$this->r38_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r38_regist,$this->r38_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3905,'$this->r38_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,9981,'$this->r38_instit','I')");
       $resac = db_query("insert into db_acount values($acount,550,3905,'','".AddSlashes(pg_result($resaco,0,'r38_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3906,'','".AddSlashes(pg_result($resaco,0,'r38_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3907,'','".AddSlashes(pg_result($resaco,0,'r38_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3908,'','".AddSlashes(pg_result($resaco,0,'r38_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3909,'','".AddSlashes(pg_result($resaco,0,'r38_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3910,'','".AddSlashes(pg_result($resaco,0,'r38_vincul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3911,'','".AddSlashes(pg_result($resaco,0,'r38_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3912,'','".AddSlashes(pg_result($resaco,0,'r38_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3913,'','".AddSlashes(pg_result($resaco,0,'r38_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3914,'','".AddSlashes(pg_result($resaco,0,'r38_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3915,'','".AddSlashes(pg_result($resaco,0,'r38_agenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3916,'','".AddSlashes(pg_result($resaco,0,'r38_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3917,'','".AddSlashes(pg_result($resaco,0,'r38_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3918,'','".AddSlashes(pg_result($resaco,0,'r38_previd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3919,'','".AddSlashes(pg_result($resaco,0,'r38_liq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3920,'','".AddSlashes(pg_result($resaco,0,'r38_prov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3921,'','".AddSlashes(pg_result($resaco,0,'r38_desc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,3922,'','".AddSlashes(pg_result($resaco,0,'r38_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,550,9981,'','".AddSlashes(pg_result($resaco,0,'r38_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($r38_regist=null,$r38_instit=null) {
      $this->atualizacampos();
     $sql = " update folha set ";
     $virgula = "";
     if(trim($this->r38_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_regist"])){
       $sql  .= $virgula." r38_regist = $this->r38_regist ";
       $virgula = ",";
       if(trim($this->r38_regist) == null ){
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r38_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_nome"])){
       $sql  .= $virgula." r38_nome = '$this->r38_nome' ";
       $virgula = ",";
       if(trim($this->r38_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "r38_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_numcgm"])){
       $sql  .= $virgula." r38_numcgm = $this->r38_numcgm ";
       $virgula = ",";
       if(trim($this->r38_numcgm) == null ){
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "r38_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_regime"])){
        if(trim($this->r38_regime)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r38_regime"])){
           $this->r38_regime = "0" ;
        }
       $sql  .= $virgula." r38_regime = $this->r38_regime ";
       $virgula = ",";
     }
     if(trim($this->r38_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_lotac"])){
       $sql  .= $virgula." r38_lotac = '$this->r38_lotac' ";
       $virgula = ",";
     }
     if(trim($this->r38_vincul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_vincul"])){
       $sql  .= $virgula." r38_vincul = '$this->r38_vincul' ";
       $virgula = ",";
     }
     if(trim($this->r38_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_padrao"])){
       $sql  .= $virgula." r38_padrao = '$this->r38_padrao' ";
       $virgula = ",";
     }
     if(trim($this->r38_salari)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_salari"])){
        if(trim($this->r38_salari)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r38_salari"])){
           $this->r38_salari = "0" ;
        }
       $sql  .= $virgula." r38_salari = $this->r38_salari ";
       $virgula = ",";
     }
     if(trim($this->r38_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_funcao"])){
       $sql  .= $virgula." r38_funcao = '$this->r38_funcao' ";
       $virgula = ",";
     }
     if(trim($this->r38_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_banco"])){
       $sql  .= $virgula." r38_banco = '$this->r38_banco' ";
       $virgula = ",";
     }
     if(trim($this->r38_agenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_agenc"])){
       $sql  .= $virgula." r38_agenc = '$this->r38_agenc' ";
       $virgula = ",";
     }
     if(trim($this->r38_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_conta"])){
       $sql  .= $virgula." r38_conta = '$this->r38_conta' ";
       $virgula = ",";
     }
     if(trim($this->r38_situac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_situac"])){
        if(trim($this->r38_situac)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r38_situac"])){
           $this->r38_situac = "0" ;
        }
       $sql  .= $virgula." r38_situac = $this->r38_situac ";
       $virgula = ",";
     }
     if(trim($this->r38_previd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_previd"])){
        if(trim($this->r38_previd)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r38_previd"])){
           $this->r38_previd = "0" ;
        }
       $sql  .= $virgula." r38_previd = $this->r38_previd ";
       $virgula = ",";
     }
     if(trim($this->r38_liq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_liq"])){
       $sql  .= $virgula." r38_liq = $this->r38_liq ";
       $virgula = ",";
       if(trim($this->r38_liq) == null ){
         $this->erro_sql = " Campo Salario Liquido nao Informado.";
         $this->erro_campo = "r38_liq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_prov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_prov"])){
       $sql  .= $virgula." r38_prov = $this->r38_prov ";
       $virgula = ",";
       if(trim($this->r38_prov) == null ){
         $this->erro_sql = " Campo Proventos do funcionario nao Informado.";
         $this->erro_campo = "r38_prov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_desc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_desc"])){
       $sql  .= $virgula." r38_desc = $this->r38_desc ";
       $virgula = ",";
       if(trim($this->r38_desc) == null ){
         $this->erro_sql = " Campo Descontos do funcionario nao Informado.";
         $this->erro_campo = "r38_desc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r38_proc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_proc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r38_proc_dia"] !="") ){
       $sql  .= $virgula." r38_proc = '$this->r38_proc' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["r38_proc_dia"])){
         $sql  .= $virgula." r38_proc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r38_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r38_instit"])){
       $sql  .= $virgula." r38_instit = $this->r38_instit ";
       $virgula = ",";
       if(trim($this->r38_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "r38_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r38_regist!=null){
       $sql .= " r38_regist = $this->r38_regist";
     }
     if($r38_instit!=null){
       $sql .= " and  r38_instit = $this->r38_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r38_regist,$this->r38_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3905,'$this->r38_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,9981,'$this->r38_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_regist"]))
           $resac = db_query("insert into db_acount values($acount,550,3905,'".AddSlashes(pg_result($resaco,$conresaco,'r38_regist'))."','$this->r38_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_nome"]))
           $resac = db_query("insert into db_acount values($acount,550,3906,'".AddSlashes(pg_result($resaco,$conresaco,'r38_nome'))."','$this->r38_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,550,3907,'".AddSlashes(pg_result($resaco,$conresaco,'r38_numcgm'))."','$this->r38_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_regime"]))
           $resac = db_query("insert into db_acount values($acount,550,3908,'".AddSlashes(pg_result($resaco,$conresaco,'r38_regime'))."','$this->r38_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_lotac"]))
           $resac = db_query("insert into db_acount values($acount,550,3909,'".AddSlashes(pg_result($resaco,$conresaco,'r38_lotac'))."','$this->r38_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_vincul"]))
           $resac = db_query("insert into db_acount values($acount,550,3910,'".AddSlashes(pg_result($resaco,$conresaco,'r38_vincul'))."','$this->r38_vincul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_padrao"]))
           $resac = db_query("insert into db_acount values($acount,550,3911,'".AddSlashes(pg_result($resaco,$conresaco,'r38_padrao'))."','$this->r38_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_salari"]))
           $resac = db_query("insert into db_acount values($acount,550,3912,'".AddSlashes(pg_result($resaco,$conresaco,'r38_salari'))."','$this->r38_salari',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_funcao"]))
           $resac = db_query("insert into db_acount values($acount,550,3913,'".AddSlashes(pg_result($resaco,$conresaco,'r38_funcao'))."','$this->r38_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_banco"]))
           $resac = db_query("insert into db_acount values($acount,550,3914,'".AddSlashes(pg_result($resaco,$conresaco,'r38_banco'))."','$this->r38_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_agenc"]))
           $resac = db_query("insert into db_acount values($acount,550,3915,'".AddSlashes(pg_result($resaco,$conresaco,'r38_agenc'))."','$this->r38_agenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_conta"]))
           $resac = db_query("insert into db_acount values($acount,550,3916,'".AddSlashes(pg_result($resaco,$conresaco,'r38_conta'))."','$this->r38_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_situac"]))
           $resac = db_query("insert into db_acount values($acount,550,3917,'".AddSlashes(pg_result($resaco,$conresaco,'r38_situac'))."','$this->r38_situac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_previd"]))
           $resac = db_query("insert into db_acount values($acount,550,3918,'".AddSlashes(pg_result($resaco,$conresaco,'r38_previd'))."','$this->r38_previd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_liq"]))
           $resac = db_query("insert into db_acount values($acount,550,3919,'".AddSlashes(pg_result($resaco,$conresaco,'r38_liq'))."','$this->r38_liq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_prov"]))
           $resac = db_query("insert into db_acount values($acount,550,3920,'".AddSlashes(pg_result($resaco,$conresaco,'r38_prov'))."','$this->r38_prov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_desc"]))
           $resac = db_query("insert into db_acount values($acount,550,3921,'".AddSlashes(pg_result($resaco,$conresaco,'r38_desc'))."','$this->r38_desc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_proc"]))
           $resac = db_query("insert into db_acount values($acount,550,3922,'".AddSlashes(pg_result($resaco,$conresaco,'r38_proc'))."','$this->r38_proc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r38_instit"]))
           $resac = db_query("insert into db_acount values($acount,550,9981,'".AddSlashes(pg_result($resaco,$conresaco,'r38_instit'))."','$this->r38_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Auxiliar de Cálculos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r38_regist."-".$this->r38_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Auxiliar de Cálculos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r38_regist."-".$this->r38_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r38_regist."-".$this->r38_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($r38_regist=null,$r38_instit=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r38_regist,$r38_instit));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3905,'$r38_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,9981,'$r38_instit','E')");
         $resac = db_query("insert into db_acount values($acount,550,3905,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3906,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3907,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3908,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3909,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3910,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_vincul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3911,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3912,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3913,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3914,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3915,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_agenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3916,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3917,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_situac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3918,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_previd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3919,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_liq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3920,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_prov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3921,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_desc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,3922,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_proc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,550,9981,'','".AddSlashes(pg_result($resaco,$iresaco,'r38_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from folha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r38_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r38_regist = $r38_regist ";
        }
        if($r38_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r38_instit = $r38_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Auxiliar de Cálculos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r38_regist."-".$r38_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Auxiliar de Cálculos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r38_regist."-".$r38_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r38_regist."-".$r38_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:folha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r38_regist=null,$r38_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from folha ";
     $sql .= "      inner join db_config  on  db_config.codigo = folha.r38_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
       }
       if($r38_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " folha.r38_instit = $r38_instit ";
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
   function sql_query_cgm ( $r38_regist=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from folha ";
     $sql .= "      inner join cgm on cgm.z01_numcgm = folha.r38_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
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
   function sql_query_file ( $r38_regist=null,$r38_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from folha ";
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
       }
       if($r38_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " folha.r38_instit = $r38_instit ";
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
   function sql_query_gerarq ( $r38_regist=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from folha                                                               ";
     $sql .= "      inner join cgm    on cgm.z01_numcgm = folha.r38_numcgm              ";
     $sql .= "      inner join rhlota on r70_codigo     = to_number(r38_lotac,'9999')   ";
     $sql .= "                       and r70_instit     = " . db_getsession('DB_instit') ;

     $sql .= "      inner join (
                                select
                                       distinct
                                       rh25_codigo,
                                       rh25_recurso
                                from rhlotavinc
				where rh25_anousu = ".db_getsession('DB_anousu')."
                               )
                                as rhlotavinc on rh25_codigo = r70_codigo                    ";

     $sql .= "      inner join rhcontasrec   on rh41_codigo  = rh25_recurso                  ";
     $sql .="                               and rh41_anousu  = " . db_getsession("DB_anousu") ;
     $sql .= "                              and rh41_instit  = " . db_getsession("DB_instit") ;
     $sql .= "      inner join saltes        on k13_conta    = rh41_conta                    ";
     $sql .= "      inner join conplanoreduz on c61_reduz    = k13_reduz                     ";
     $sql .= "                              and c61_anousu   = " . db_getsession("DB_anousu") ;
     $sql .= "      inner join conplanoconta on c63_codcon   = c61_codcon                    ";
     $sql .= "                              and c63_anousu   =c61_anousu                     ";
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
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
   function sql_query_gerarqbag ( $r38_regist=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from folha ";
     $sql .= "      inner join cgm on cgm.z01_numcgm = folha.r38_numcgm ";
     $sql .= "      inner join rhlota on r70_codigo = to_number(r38_lotac,'9999') ";
     $sql .= "                       and r70_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join rhpessoalmov on rhpessoalmov.rh02_regist = folha.r38_regist";
     $sql .= "                             and rh02_anousu = ".db_anofolha();
     $sql .= "                             and rh02_mesusu = ".db_mesfolha();
     $sql .= "                             and rh02_instit = ".db_getsession("DB_instit");  
     $sql .= "      inner join rhpessoalmovcontabancaria on rhpessoalmov.rh02_seqpes = rhpessoalmovcontabancaria.rh138_rhpessoalmov ";
     $sql .= "                                          and rhpessoalmov.rh02_instit = rhpessoalmovcontabancaria.rh138_instit";
     $sql .= "      inner join contabancaria on contabancaria.db83_sequencial = rhpessoalmovcontabancaria.rh138_contabancaria";
     $sql .= "      inner join bancoagencia  on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
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
   function sql_query_gerarqdae ( $r38_regist=null,$campos="*",$ordem=null,$dbwhere="",$ano, $mes){
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

     if($ano == null || trim($ano) == ""){
       $ano = db_anofolha();
     }
     if($mes == null || trim($mes) == ""){
       $mes = db_mesfolha();
     }

     $sql .= " from folha ";
     $sql .= "      inner join cgm on cgm.z01_numcgm = folha.r38_numcgm ";
     $sql .= "      inner join rhlota on r70_codigo = to_number(r38_lotac,'9999') ";
     $sql .= "                       and r70_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join rhpessoalmov on rh02_anousu = ".$ano."
			                               		   and rh02_regist = r38_regist
																					 and rh02_mesusu = $mes
																					 and rh02_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join rhregime on rh30_codreg = rh02_codreg ";
     $sql .= "                         and rh30_instit = ".db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($r38_regist!=null ){
         $sql2 .= " where folha.r38_regist = $r38_regist ";
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

  function sql_query_gerarqbagRecurso ( $r38_regist=null,$campos="*",$ordem=null,$dbwhere=""){
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

  	$sql .= " from folha ";
  	$sql .= "      inner join cgm on cgm.z01_numcgm = folha.r38_numcgm ";
  	$sql .= "      inner join rhlota on r70_codigo = to_number(r38_lotac,'9999') ";
  	$sql .= "                       and r70_instit = ".db_getsession("DB_instit");
  	$sql .= "      inner join rhlotavinc   on rhlotavinc.rh25_codigo   = rhlota.r70_codigo";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($r38_regist!=null ){
  			$sql2 .= " where folha.r38_regist = $r38_regist ";
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
}
?>