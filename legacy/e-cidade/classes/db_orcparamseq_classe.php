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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparamseq
class cl_orcparamseq {
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
   var $o69_codparamrel = 0;
   var $o69_codseq = 0;
   var $o69_descr = null;
   var $o69_grupo = 0;
   var $o69_grupoexclusao = 0;
   var $o69_nivel = 0;
   var $o69_libnivel = 'f';
   var $o69_librec = 'f';
   var $o69_libsubfunc = 'f';
   var $o69_libfunc = 'f';
   var $o69_verificaano = 'f';
   var $o69_labelrel = null;
   var $o69_manual = 'f';
   var $o69_totalizador = 'f';
   var $o69_ordem = 0;
   var $o69_nivellinha = 0;
   var $o69_observacao = null;
   var $o69_desdobrarlinha = 'f';
   var $o69_origem = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o69_codparamrel = int4 = codigo do relatorio
                 o69_codseq = int4 = sequencia da tabela
                 o69_descr = varchar(60) = descrição
                 o69_grupo = int4 = Grupo de Contas
                 o69_grupoexclusao = int4 = Grupo de Contas Exclusão
                 o69_nivel = int4 = Nivel/Comparação
                 o69_libnivel = bool = Libera Nivel
                 o69_librec = bool = libera recurso
                 o69_libsubfunc = bool = Libera SubFunção
                 o69_libfunc = bool = Libera Função
                 o69_verificaano = bool = Verifica Ano
                 o69_labelrel = varchar(200) = Label Relatório
                 o69_manual = bool = Manual
                 o69_totalizador = bool = Linha Totalizadora
                 o69_ordem = int4 = Ordem
                 o69_nivellinha = int4 = Nivel Linha
                 o69_observacao = text = Observacao
                 o69_desdobrarlinha = bool = Detalhamento Analítico
                 o69_origem = int4 = Origem dos Dados
                 ";
   //funcao construtor da classe
   function cl_orcparamseq() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseq");
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
       $this->o69_codparamrel = ($this->o69_codparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"]:$this->o69_codparamrel);
       $this->o69_codseq = ($this->o69_codseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codseq"]:$this->o69_codseq);
       $this->o69_descr = ($this->o69_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_descr"]:$this->o69_descr);
       $this->o69_grupo = ($this->o69_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_grupo"]:$this->o69_grupo);
       $this->o69_grupoexclusao = ($this->o69_grupoexclusao == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"]:$this->o69_grupoexclusao);
       $this->o69_nivel = ($this->o69_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_nivel"]:$this->o69_nivel);
       $this->o69_libnivel = ($this->o69_libnivel == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_libnivel"]:$this->o69_libnivel);
       $this->o69_librec = ($this->o69_librec == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_librec"]:$this->o69_librec);
       $this->o69_libsubfunc = ($this->o69_libsubfunc == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_libsubfunc"]:$this->o69_libsubfunc);
       $this->o69_libfunc = ($this->o69_libfunc == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_libfunc"]:$this->o69_libfunc);
       $this->o69_verificaano = ($this->o69_verificaano == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_verificaano"]:$this->o69_verificaano);
       $this->o69_labelrel = ($this->o69_labelrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_labelrel"]:$this->o69_labelrel);
       $this->o69_manual = ($this->o69_manual == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_manual"]:$this->o69_manual);
       $this->o69_totalizador = ($this->o69_totalizador == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_totalizador"]:$this->o69_totalizador);
       $this->o69_ordem = ($this->o69_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_ordem"]:$this->o69_ordem);
       $this->o69_nivellinha = ($this->o69_nivellinha == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_nivellinha"]:$this->o69_nivellinha);
       $this->o69_observacao = ($this->o69_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_observacao"]:$this->o69_observacao);
       $this->o69_desdobrarlinha = ($this->o69_desdobrarlinha == "f"?@$GLOBALS["HTTP_POST_VARS"]["o69_desdobrarlinha"]:$this->o69_desdobrarlinha);
       $this->o69_origem = ($this->o69_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_origem"]:$this->o69_origem);
     }else{
       $this->o69_codparamrel = ($this->o69_codparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"]:$this->o69_codparamrel);
       $this->o69_codseq = ($this->o69_codseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codseq"]:$this->o69_codseq);
     }
   }
   // funcao para inclusao
   function incluir ($o69_codparamrel,$o69_codseq){
      $this->atualizacampos();
     if($this->o69_descr == null ){
       $this->erro_sql = " Campo descrição não informado.";
       $this->erro_campo = "o69_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_grupo == null ){
       $this->erro_sql = " Campo Grupo de Contas não informado.";
       $this->erro_campo = "o69_grupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_grupoexclusao == null ){
       $this->o69_grupoexclusao = "0";
     }
     if($this->o69_nivel == null ){
       $this->o69_nivel = "0";
     }
     if($this->o69_libnivel == null ){
       $this->erro_sql = " Campo Libera Nivel não informado.";
       $this->erro_campo = "o69_libnivel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_librec == null ){
       $this->erro_sql = " Campo libera recurso não informado.";
       $this->erro_campo = "o69_librec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_libsubfunc == null ){
       $this->erro_sql = " Campo Libera SubFunção não informado.";
       $this->erro_campo = "o69_libsubfunc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_libfunc == null ){
       $this->erro_sql = " Campo Libera Função não informado.";
       $this->erro_campo = "o69_libfunc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_verificaano == null ){
       $this->erro_sql = " Campo Verifica Ano não informado.";
       $this->erro_campo = "o69_verificaano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_labelrel == null ){
       $this->erro_sql = " Campo Label Relatório não informado.";
       $this->erro_campo = "o69_labelrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_manual == null ){
       $this->erro_sql = " Campo Manual não informado.";
       $this->erro_campo = "o69_manual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_totalizador == null ){
       $this->o69_totalizador = "false";
     }
     if($this->o69_ordem == null ){
       $this->o69_ordem = "0";
     }
     if($this->o69_nivellinha == null ){
       $this->o69_nivellinha = "0";
     }
     if($this->o69_desdobrarlinha == null ){
       $this->o69_desdobrarlinha = "false";
     }
     if($this->o69_origem == null ){
       $this->o69_origem = "0";
     }
       $this->o69_codparamrel = $o69_codparamrel;
       $this->o69_codseq = $o69_codseq;
     if(($this->o69_codparamrel == null) || ($this->o69_codparamrel == "") ){
       $this->erro_sql = " Campo o69_codparamrel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o69_codseq == null) || ($this->o69_codseq == "") ){
       $this->erro_sql = " Campo o69_codseq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseq(
                                       o69_codparamrel
                                      ,o69_codseq
                                      ,o69_descr
                                      ,o69_grupo
                                      ,o69_grupoexclusao
                                      ,o69_nivel
                                      ,o69_libnivel
                                      ,o69_librec
                                      ,o69_libsubfunc
                                      ,o69_libfunc
                                      ,o69_verificaano
                                      ,o69_labelrel
                                      ,o69_manual
                                      ,o69_totalizador
                                      ,o69_ordem
                                      ,o69_nivellinha
                                      ,o69_observacao
                                      ,o69_desdobrarlinha
                                      ,o69_origem
                       )
                values (
                                $this->o69_codparamrel
                               ,$this->o69_codseq
                               ,'$this->o69_descr'
                               ,$this->o69_grupo
                               ,$this->o69_grupoexclusao
                               ,$this->o69_nivel
                               ,'$this->o69_libnivel'
                               ,'$this->o69_librec'
                               ,'$this->o69_libsubfunc'
                               ,'$this->o69_libfunc'
                               ,'$this->o69_verificaano'
                               ,'$this->o69_labelrel'
                               ,'$this->o69_manual'
                               ,'$this->o69_totalizador'
                               ,$this->o69_ordem
                               ,$this->o69_nivellinha
                               ,'$this->o69_observacao'
                               ,'$this->o69_desdobrarlinha'
                               ,$this->o69_origem
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "o69 ($this->o69_codparamrel."-".$this->o69_codseq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "o69 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "o69 ($this->o69_codparamrel."-".$this->o69_codseq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o69_codparamrel,$this->o69_codseq  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6577,'$this->o69_codparamrel','I')");
         $resac = db_query("insert into db_acountkey values($acount,6578,'$this->o69_codseq','I')");
         $resac = db_query("insert into db_acount values($acount,1082,6577,'','".AddSlashes(pg_result($resaco,0,'o69_codparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,6578,'','".AddSlashes(pg_result($resaco,0,'o69_codseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,6579,'','".AddSlashes(pg_result($resaco,0,'o69_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8855,'','".AddSlashes(pg_result($resaco,0,'o69_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8856,'','".AddSlashes(pg_result($resaco,0,'o69_grupoexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8988,'','".AddSlashes(pg_result($resaco,0,'o69_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8989,'','".AddSlashes(pg_result($resaco,0,'o69_libnivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8990,'','".AddSlashes(pg_result($resaco,0,'o69_librec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,8991,'','".AddSlashes(pg_result($resaco,0,'o69_libsubfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,10252,'','".AddSlashes(pg_result($resaco,0,'o69_libfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,11807,'','".AddSlashes(pg_result($resaco,0,'o69_verificaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,14102,'','".AddSlashes(pg_result($resaco,0,'o69_labelrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,14103,'','".AddSlashes(pg_result($resaco,0,'o69_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,17722,'','".AddSlashes(pg_result($resaco,0,'o69_totalizador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,17723,'','".AddSlashes(pg_result($resaco,0,'o69_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,17726,'','".AddSlashes(pg_result($resaco,0,'o69_nivellinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,17727,'','".AddSlashes(pg_result($resaco,0,'o69_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,17910,'','".AddSlashes(pg_result($resaco,0,'o69_desdobrarlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1082,20474,'','".AddSlashes(pg_result($resaco,0,'o69_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($o69_codparamrel=null,$o69_codseq=null) {
      $this->atualizacampos();
     $sql = " update orcparamseq set ";
     $virgula = "";
     if(trim($this->o69_codparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"])){
       $sql  .= $virgula." o69_codparamrel = $this->o69_codparamrel ";
       $virgula = ",";
       if(trim($this->o69_codparamrel) == null ){
         $this->erro_sql = " Campo codigo do relatorio não informado.";
         $this->erro_campo = "o69_codparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codseq"])){
       $sql  .= $virgula." o69_codseq = $this->o69_codseq ";
       $virgula = ",";
       if(trim($this->o69_codseq) == null ){
         $this->erro_sql = " Campo sequencia da tabela não informado.";
         $this->erro_campo = "o69_codseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_descr"])){
       $sql  .= $virgula." o69_descr = '$this->o69_descr' ";
       $virgula = ",";
       if(trim($this->o69_descr) == null ){
         $this->erro_sql = " Campo descrição não informado.";
         $this->erro_campo = "o69_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_grupo"])){
       $sql  .= $virgula." o69_grupo = $this->o69_grupo ";
       $virgula = ",";
       if(trim($this->o69_grupo) == null ){
         $this->erro_sql = " Campo Grupo de Contas não informado.";
         $this->erro_campo = "o69_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_grupoexclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"])){
        if(trim($this->o69_grupoexclusao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"])){
           $this->o69_grupoexclusao = "0" ;
        }
       $sql  .= $virgula." o69_grupoexclusao = $this->o69_grupoexclusao ";
       $virgula = ",";
     }
     if(trim($this->o69_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"])){
        if(trim($this->o69_nivel)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"])){
           $this->o69_nivel = "0" ;
        }
       $sql  .= $virgula." o69_nivel = $this->o69_nivel ";
       $virgula = ",";
     }
     if(trim($this->o69_libnivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libnivel"])){
       $sql  .= $virgula." o69_libnivel = '$this->o69_libnivel' ";
       $virgula = ",";
       if(trim($this->o69_libnivel) == null ){
         $this->erro_sql = " Campo Libera Nivel não informado.";
         $this->erro_campo = "o69_libnivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_librec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_librec"])){
       $sql  .= $virgula." o69_librec = '$this->o69_librec' ";
       $virgula = ",";
       if(trim($this->o69_librec) == null ){
         $this->erro_sql = " Campo libera recurso não informado.";
         $this->erro_campo = "o69_librec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_libsubfunc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libsubfunc"])){
       $sql  .= $virgula." o69_libsubfunc = '$this->o69_libsubfunc' ";
       $virgula = ",";
       if(trim($this->o69_libsubfunc) == null ){
         $this->erro_sql = " Campo Libera SubFunção não informado.";
         $this->erro_campo = "o69_libsubfunc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_libfunc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libfunc"])){
       $sql  .= $virgula." o69_libfunc = '$this->o69_libfunc' ";
       $virgula = ",";
       if(trim($this->o69_libfunc) == null ){
         $this->erro_sql = " Campo Libera Função não informado.";
         $this->erro_campo = "o69_libfunc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_verificaano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_verificaano"])){
       $sql  .= $virgula." o69_verificaano = '$this->o69_verificaano' ";
       $virgula = ",";
       if(trim($this->o69_verificaano) == null ){
         $this->erro_sql = " Campo Verifica Ano não informado.";
         $this->erro_campo = "o69_verificaano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_labelrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_labelrel"])){
       $sql  .= $virgula." o69_labelrel = '$this->o69_labelrel' ";
       $virgula = ",";
       if(trim($this->o69_labelrel) == null ){
         $this->erro_sql = " Campo Label Relatório não informado.";
         $this->erro_campo = "o69_labelrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_manual"])){
       $sql  .= $virgula." o69_manual = '$this->o69_manual' ";
       $virgula = ",";
       if(trim($this->o69_manual) == null ){
         $this->erro_sql = " Campo Manual não informado.";
         $this->erro_campo = "o69_manual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_totalizador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_totalizador"])){
       $sql  .= $virgula." o69_totalizador = '$this->o69_totalizador' ";
       $virgula = ",";
     }
     if(trim($this->o69_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_ordem"])){
        if(trim($this->o69_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_ordem"])){
           $this->o69_ordem = "0" ;
        }
       $sql  .= $virgula." o69_ordem = $this->o69_ordem ";
       $virgula = ",";
     }
     if(trim($this->o69_nivellinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_nivellinha"])){
        if(trim($this->o69_nivellinha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_nivellinha"])){
           $this->o69_nivellinha = "0" ;
        }
       $sql  .= $virgula." o69_nivellinha = $this->o69_nivellinha ";
       $virgula = ",";
     }
     if(trim($this->o69_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_observacao"])){
       $sql  .= $virgula." o69_observacao = '$this->o69_observacao' ";
       $virgula = ",";
     }
     if(trim($this->o69_desdobrarlinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_desdobrarlinha"])){
       $sql  .= $virgula." o69_desdobrarlinha = '$this->o69_desdobrarlinha' ";
       $virgula = ",";
     }
     if(trim($this->o69_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_origem"])){
        if(trim($this->o69_origem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_origem"])){
           $this->o69_origem = "0" ;
        }
       $sql  .= $virgula." o69_origem = $this->o69_origem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o69_codparamrel!=null){
       $sql .= " o69_codparamrel = $this->o69_codparamrel";
     }
     if($o69_codseq!=null){
       $sql .= " and  o69_codseq = $this->o69_codseq";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o69_codparamrel,$this->o69_codseq));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6577,'$this->o69_codparamrel','A')");
           $resac = db_query("insert into db_acountkey values($acount,6578,'$this->o69_codseq','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"]) || $this->o69_codparamrel != "")
             $resac = db_query("insert into db_acount values($acount,1082,6577,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codparamrel'))."','$this->o69_codparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codseq"]) || $this->o69_codseq != "")
             $resac = db_query("insert into db_acount values($acount,1082,6578,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codseq'))."','$this->o69_codseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_descr"]) || $this->o69_descr != "")
             $resac = db_query("insert into db_acount values($acount,1082,6579,'".AddSlashes(pg_result($resaco,$conresaco,'o69_descr'))."','$this->o69_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_grupo"]) || $this->o69_grupo != "")
             $resac = db_query("insert into db_acount values($acount,1082,8855,'".AddSlashes(pg_result($resaco,$conresaco,'o69_grupo'))."','$this->o69_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"]) || $this->o69_grupoexclusao != "")
             $resac = db_query("insert into db_acount values($acount,1082,8856,'".AddSlashes(pg_result($resaco,$conresaco,'o69_grupoexclusao'))."','$this->o69_grupoexclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"]) || $this->o69_nivel != "")
             $resac = db_query("insert into db_acount values($acount,1082,8988,'".AddSlashes(pg_result($resaco,$conresaco,'o69_nivel'))."','$this->o69_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libnivel"]) || $this->o69_libnivel != "")
             $resac = db_query("insert into db_acount values($acount,1082,8989,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libnivel'))."','$this->o69_libnivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_librec"]) || $this->o69_librec != "")
             $resac = db_query("insert into db_acount values($acount,1082,8990,'".AddSlashes(pg_result($resaco,$conresaco,'o69_librec'))."','$this->o69_librec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libsubfunc"]) || $this->o69_libsubfunc != "")
             $resac = db_query("insert into db_acount values($acount,1082,8991,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libsubfunc'))."','$this->o69_libsubfunc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libfunc"]) || $this->o69_libfunc != "")
             $resac = db_query("insert into db_acount values($acount,1082,10252,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libfunc'))."','$this->o69_libfunc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_verificaano"]) || $this->o69_verificaano != "")
             $resac = db_query("insert into db_acount values($acount,1082,11807,'".AddSlashes(pg_result($resaco,$conresaco,'o69_verificaano'))."','$this->o69_verificaano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_labelrel"]) || $this->o69_labelrel != "")
             $resac = db_query("insert into db_acount values($acount,1082,14102,'".AddSlashes(pg_result($resaco,$conresaco,'o69_labelrel'))."','$this->o69_labelrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_manual"]) || $this->o69_manual != "")
             $resac = db_query("insert into db_acount values($acount,1082,14103,'".AddSlashes(pg_result($resaco,$conresaco,'o69_manual'))."','$this->o69_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_totalizador"]) || $this->o69_totalizador != "")
             $resac = db_query("insert into db_acount values($acount,1082,17722,'".AddSlashes(pg_result($resaco,$conresaco,'o69_totalizador'))."','$this->o69_totalizador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_ordem"]) || $this->o69_ordem != "")
             $resac = db_query("insert into db_acount values($acount,1082,17723,'".AddSlashes(pg_result($resaco,$conresaco,'o69_ordem'))."','$this->o69_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_nivellinha"]) || $this->o69_nivellinha != "")
             $resac = db_query("insert into db_acount values($acount,1082,17726,'".AddSlashes(pg_result($resaco,$conresaco,'o69_nivellinha'))."','$this->o69_nivellinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_observacao"]) || $this->o69_observacao != "")
             $resac = db_query("insert into db_acount values($acount,1082,17727,'".AddSlashes(pg_result($resaco,$conresaco,'o69_observacao'))."','$this->o69_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_desdobrarlinha"]) || $this->o69_desdobrarlinha != "")
             $resac = db_query("insert into db_acount values($acount,1082,17910,'".AddSlashes(pg_result($resaco,$conresaco,'o69_desdobrarlinha'))."','$this->o69_desdobrarlinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o69_origem"]) || $this->o69_origem != "")
             $resac = db_query("insert into db_acount values($acount,1082,20474,'".AddSlashes(pg_result($resaco,$conresaco,'o69_origem'))."','$this->o69_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "o69 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "o69 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($o69_codparamrel=null,$o69_codseq=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($o69_codparamrel,$o69_codseq));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6577,'$o69_codparamrel','E')");
           $resac  = db_query("insert into db_acountkey values($acount,6578,'$o69_codseq','E')");
           $resac  = db_query("insert into db_acount values($acount,1082,6577,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,6578,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,6579,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8855,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8856,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_grupoexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8988,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8989,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_libnivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8990,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_librec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,8991,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_libsubfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,10252,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_libfunc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,11807,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_verificaano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,14102,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_labelrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,14103,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_manual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,17722,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_totalizador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,17723,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,17726,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_nivellinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,17727,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,17910,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_desdobrarlinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1082,20474,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orcparamseq
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o69_codparamrel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o69_codparamrel = $o69_codparamrel ";
        }
        if($o69_codseq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o69_codseq = $o69_codseq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "o69 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o69_codparamrel."-".$o69_codseq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "o69 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o69_codparamrel."-".$o69_codseq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o69_codparamrel."-".$o69_codseq;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseq";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $o69_codparamrel=null,$o69_codseq=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcparamseq ";
     $sql .= "      inner join orcparamrel       on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql .= "      inner join orcparamrelgrupo  on  orcparamrelgrupo.o112_sequencial = orcparamrel.o42_orcparamrelgrupo";
     $sql .= "      left join  orcparamnivel     on  o44_codparrel = orcparamseq.o69_codparamrel ";
     $sql .= "                                  and  o44_sequencia = o69_codseq ";
     $sql .= "                                  and  o44_anousu    = " . db_getsession('DB_anousu');
     $sql2 = "";
     if($dbwhere==""){
       if($o69_codparamrel!=null ){
         $sql2 .= " where orcparamseq.o69_codparamrel = $o69_codparamrel ";
       }
       if($o69_codseq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcparamseq.o69_codseq = $o69_codseq ";
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
   function sql_query_file ( $o69_codparamrel=null,$o69_codseq=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcparamseq ";
     $sql2 = "";
     if($dbwhere==""){
       if($o69_codparamrel!=null ){
         $sql2 .= " where orcparamseq.o69_codparamrel = $o69_codparamrel ";
       }
       if($o69_codseq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcparamseq.o69_codseq = $o69_codseq ";
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
   function alterar_where($o69_codparamrel=null,$o69_codseq=null,$dbwhere=null) {
      $this->atualizacampos();
     $sql = " update orcparamseq set ";
     $virgula = "";
     if(trim($this->o69_codparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"])){
       $sql  .= $virgula." o69_codparamrel = $this->o69_codparamrel ";
       $virgula = ",";
       if(trim($this->o69_codparamrel) == null ){
         $this->erro_sql = " Campo codigo do relatorio nao Informado.";
         $this->erro_campo = "o69_codparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codseq"])){
       $sql  .= $virgula." o69_codseq = $this->o69_codseq ";
       $virgula = ",";
       if(trim($this->o69_codseq) == null ){
         $this->erro_sql = " Campo sequencia da tabela nao Informado.";
         $this->erro_campo = "o69_codseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_totalizador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_totalizador"])){
       $sql  .= $virgula." o69_totalizador = '$this->o69_totalizador' ";
       $virgula = ",";
     }
     if(trim($this->o69_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_descr"])){
       $sql  .= $virgula." o69_descr = '$this->o69_descr' ";
       $virgula = ",";
       if(trim($this->o69_descr) == null ){
         $this->erro_sql = " Campo descrição nao Informado.";
         $this->erro_campo = "o69_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_grupo"])){
       $sql  .= $virgula." o69_grupo = $this->o69_grupo ";
       $virgula = ",";
       if(trim($this->o69_grupo) == null ){
         $this->erro_sql = " Campo Grupo de Contas nao Informado.";
         $this->erro_campo = "o69_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

		 if(trim($this->o69_grupoexclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"])){
        if(trim($this->o69_grupoexclusao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"])){
           $this->o69_grupoexclusao = "0" ;
        }
       $sql  .= $virgula." o69_grupoexclusao = $this->o69_grupoexclusao ";
       $virgula = ",";
     }

		 if(trim($this->o69_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"])){
        if(trim($this->o69_nivel)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"])){
           $this->o69_nivel = "0" ;
        }
       $sql  .= $virgula." o69_nivel = $this->o69_nivel ";
       $virgula = ",";
     }


     if(trim($this->o69_libnivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libnivel"])){
       $sql  .= $virgula." o69_libnivel = '$this->o69_libnivel' ";
       $virgula = ",";
       if(trim($this->o69_libnivel) == null ){
         $this->erro_sql = " Campo Libera Nivel nao Informado.";
         $this->erro_campo = "o69_libnivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_librec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_librec"])){
       $sql  .= $virgula." o69_librec = '$this->o69_librec' ";
       $virgula = ",";
       if(trim($this->o69_librec) == null ){
         $this->erro_sql = " Campo libera recurso nao Informado.";
         $this->erro_campo = "o69_librec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_libsubfunc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libsubfunc"])){
       $sql  .= $virgula." o69_libsubfunc = '$this->o69_libsubfunc' ";
       $virgula = ",";
       if(trim($this->o69_libsubfunc) == null ){
         $this->erro_sql = " Campo Libera SubFunção nao Informado.";
         $this->erro_campo = "o69_libsubfunc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->o69_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_origem"])){
       if(trim($this->o69_origem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o69_origem"])){
         $this->o69_origem = "0" ;
       }
       $sql  .= $virgula." o69_origem = $this->o69_origem ";
       $virgula = ",";
     }

     if(trim($this->o69_libfunc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_libfunc"])){
       $sql  .= $virgula." o69_libfunc = '$this->o69_libfunc' ";
       $virgula = ",";
       if(trim($this->o69_libfunc) == null ){
         $this->erro_sql = " Campo Libera Função nao Informado.";
         $this->erro_campo = "o69_libfunc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_verificaano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_verificaano"])){
       $sql  .= $virgula." o69_verificaano = '$this->o69_verificaano' ";
       $virgula = ",";
       if(trim($this->o69_verificaano) == null ){
         $this->erro_sql = " Campo Verifica Ano nao Informado.";
         $this->erro_campo = "o69_verificaano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_labelrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_labelrel"])){
       $sql  .= $virgula." o69_labelrel = '$this->o69_labelrel' ";
       $virgula = ",";
       if(trim($this->o69_labelrel) == null ){
         $this->erro_sql = " Campo Label Relatório nao Informado.";
         $this->erro_campo = "o69_labelrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_manual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_manual"])){
       $sql  .= $virgula." o69_manual = '$this->o69_manual' ";
       $virgula = ",";
       if(trim($this->o69_manual) == null ){
         $this->erro_sql = " Campo Manual nao Informado.";
         $this->erro_campo = "o69_manual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";

		 if (isset($dbwhere) && $dbwhere != "") {
		 	 $sql .= $dbwhere;
		 }else{
       if($o69_codparamrel!=null){
         $sql .= " o69_codparamrel = $this->o69_codparamrel";
       }
       if($o69_codseq!=null){
         $sql .= " and  o69_codseq = $this->o69_codseq";
       }
		 }

     $resaco = $this->sql_record($this->sql_query_file($this->o69_codparamrel,$this->o69_codseq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6577,'$this->o69_codparamrel','A')");
         $resac = db_query("insert into db_acountkey values($acount,6578,'$this->o69_codseq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codparamrel"]) || $this->o69_codparamrel != "")
           $resac = db_query("insert into db_acount values($acount,1082,6577,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codparamrel'))."','$this->o69_codparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codseq"]) || $this->o69_codseq != "")
           $resac = db_query("insert into db_acount values($acount,1082,6578,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codseq'))."','$this->o69_codseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_descr"]) || $this->o69_descr != "")
           $resac = db_query("insert into db_acount values($acount,1082,6579,'".AddSlashes(pg_result($resaco,$conresaco,'o69_descr'))."','$this->o69_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_grupo"]) || $this->o69_grupo != "")
           $resac = db_query("insert into db_acount values($acount,1082,8855,'".AddSlashes(pg_result($resaco,$conresaco,'o69_grupo'))."','$this->o69_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_grupoexclusao"]) || $this->o69_grupoexclusao != "")
           $resac = db_query("insert into db_acount values($acount,1082,8856,'".AddSlashes(pg_result($resaco,$conresaco,'o69_grupoexclusao'))."','$this->o69_grupoexclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_nivel"]) || $this->o69_nivel != "")
           $resac = db_query("insert into db_acount values($acount,1082,8988,'".AddSlashes(pg_result($resaco,$conresaco,'o69_nivel'))."','$this->o69_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libnivel"]) || $this->o69_libnivel != "")
           $resac = db_query("insert into db_acount values($acount,1082,8989,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libnivel'))."','$this->o69_libnivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_librec"]) || $this->o69_librec != "")
           $resac = db_query("insert into db_acount values($acount,1082,8990,'".AddSlashes(pg_result($resaco,$conresaco,'o69_librec'))."','$this->o69_librec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libsubfunc"]) || $this->o69_libsubfunc != "")
           $resac = db_query("insert into db_acount values($acount,1082,8991,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libsubfunc'))."','$this->o69_libsubfunc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_libfunc"]) || $this->o69_libfunc != "")
           $resac = db_query("insert into db_acount values($acount,1082,10252,'".AddSlashes(pg_result($resaco,$conresaco,'o69_libfunc'))."','$this->o69_libfunc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_verificaano"]) || $this->o69_verificaano != "")
           $resac = db_query("insert into db_acount values($acount,1082,11807,'".AddSlashes(pg_result($resaco,$conresaco,'o69_verificaano'))."','$this->o69_verificaano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_labelrel"]) || $this->o69_labelrel != "")
           $resac = db_query("insert into db_acount values($acount,1082,14102,'".AddSlashes(pg_result($resaco,$conresaco,'o69_labelrel'))."','$this->o69_labelrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_manual"]) || $this->o69_manual != "")
           $resac = db_query("insert into db_acount values($acount,1082,14103,'".AddSlashes(pg_result($resaco,$conresaco,'o69_manual'))."','$this->o69_manual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "o69 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "o69 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o69_codparamrel."-".$this->o69_codseq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   function sql_query_nivel( $o69_codparamrel=null,$o69_codseq=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcparamseq ";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql .= "      inner join orcparamrelgrupo  on  orcparamrelgrupo.o112_sequencial = orcparamrel.o42_orcparamrelgrupo";
     $sql .= "      left join  orcparamnivel   on  o44_codparrel = orcparamseq.o69_codparamrel ";
     $sql .= "                                and  o44_sequencia = o69_codseq ";
     $sql .= "                                and  o44_anousu = ".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($o69_codparamrel!=null ){
         $sql2 .= " where orcparamseq.o69_codparamrel = $o69_codparamrel ";
       }
       if($o69_codseq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " orcparamseq.o69_codseq = $o69_codseq ";
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
