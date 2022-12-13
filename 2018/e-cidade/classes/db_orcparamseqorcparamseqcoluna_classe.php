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

//MODULO: configuracoes
//CLASSE DA ENTIDADE orcparamseqorcparamseqcoluna
class cl_orcparamseqorcparamseqcoluna { 
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
   var $o116_sequencial = 0; 
   var $o116_codseq = 0; 
   var $o116_codparamrel = 0; 
   var $o116_orcparamseqcoluna = 0; 
   var $o116_ordem = 0; 
   var $o116_periodo = 0; 
   var $o116_formula = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o116_sequencial = int4 = Código da coluna do relatório 
                 o116_codseq = int4 = sequencia da tabela 
                 o116_codparamrel = int4 = codigo do relatorio 
                 o116_orcparamseqcoluna = int4 = Código Sequencial 
                 o116_ordem = int4 = Ordem da coluna 
                 o116_periodo = int4 = Período 
                 o116_formula = text = Fórmula de Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqorcparamseqcoluna() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqorcparamseqcoluna"); 
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
       $this->o116_sequencial = ($this->o116_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_sequencial"]:$this->o116_sequencial);
       $this->o116_codseq = ($this->o116_codseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_codseq"]:$this->o116_codseq);
       $this->o116_codparamrel = ($this->o116_codparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_codparamrel"]:$this->o116_codparamrel);
       $this->o116_orcparamseqcoluna = ($this->o116_orcparamseqcoluna == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_orcparamseqcoluna"]:$this->o116_orcparamseqcoluna);
       $this->o116_ordem = ($this->o116_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_ordem"]:$this->o116_ordem);
       $this->o116_periodo = ($this->o116_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_periodo"]:$this->o116_periodo);
       $this->o116_formula = ($this->o116_formula == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_formula"]:$this->o116_formula);
     }else{
       $this->o116_sequencial = ($this->o116_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o116_sequencial"]:$this->o116_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o116_sequencial){ 
      $this->atualizacampos();
     if($this->o116_codseq == null ){ 
       $this->erro_sql = " Campo sequencia da tabela nao Informado.";
       $this->erro_campo = "o116_codseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o116_codparamrel == null ){ 
       $this->erro_sql = " Campo codigo do relatorio nao Informado.";
       $this->erro_campo = "o116_codparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o116_orcparamseqcoluna == null ){ 
       $this->erro_sql = " Campo Código Sequencial nao Informado.";
       $this->erro_campo = "o116_orcparamseqcoluna";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o116_ordem == null ){ 
       $this->erro_sql = " Campo Ordem da coluna nao Informado.";
       $this->erro_campo = "o116_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o116_periodo == null ){ 
       $this->o116_periodo = "null";
     }
     if($o116_sequencial == "" || $o116_sequencial == null ){
       $result = db_query("select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqorcparamseqcoluna_o116_sequencial_seq do campo: o116_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o116_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqorcparamseqcoluna_o116_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o116_sequencial)){
         $this->erro_sql = " Campo o116_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o116_sequencial = $o116_sequencial; 
       }
     }
     if(($this->o116_sequencial == null) || ($this->o116_sequencial == "") ){ 
       $this->erro_sql = " Campo o116_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqorcparamseqcoluna(
                                       o116_sequencial 
                                      ,o116_codseq 
                                      ,o116_codparamrel 
                                      ,o116_orcparamseqcoluna 
                                      ,o116_ordem 
                                      ,o116_periodo 
                                      ,o116_formula 
                       )
                values (
                                $this->o116_sequencial 
                               ,$this->o116_codseq 
                               ,$this->o116_codparamrel 
                               ,$this->o116_orcparamseqcoluna 
                               ,$this->o116_ordem 
                               ,$this->o116_periodo 
                               ,'$this->o116_formula' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Colunas do relatório ($this->o116_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Colunas do relatório já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Colunas do relatório ($this->o116_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o116_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o116_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14123,'$this->o116_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2484,14123,'','".AddSlashes(pg_result($resaco,0,'o116_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,14124,'','".AddSlashes(pg_result($resaco,0,'o116_codseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,14125,'','".AddSlashes(pg_result($resaco,0,'o116_codparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,14127,'','".AddSlashes(pg_result($resaco,0,'o116_orcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,14128,'','".AddSlashes(pg_result($resaco,0,'o116_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,14126,'','".AddSlashes(pg_result($resaco,0,'o116_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2484,17724,'','".AddSlashes(pg_result($resaco,0,'o116_formula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o116_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqorcparamseqcoluna set ";
     $virgula = "";
     if(trim($this->o116_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_sequencial"])){ 
       $sql  .= $virgula." o116_sequencial = $this->o116_sequencial ";
       $virgula = ",";
       if(trim($this->o116_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da coluna do relatório nao Informado.";
         $this->erro_campo = "o116_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o116_codseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_codseq"])){ 
       $sql  .= $virgula." o116_codseq = $this->o116_codseq ";
       $virgula = ",";
       if(trim($this->o116_codseq) == null ){ 
         $this->erro_sql = " Campo sequencia da tabela nao Informado.";
         $this->erro_campo = "o116_codseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o116_codparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_codparamrel"])){ 
       $sql  .= $virgula." o116_codparamrel = $this->o116_codparamrel ";
       $virgula = ",";
       if(trim($this->o116_codparamrel) == null ){ 
         $this->erro_sql = " Campo codigo do relatorio nao Informado.";
         $this->erro_campo = "o116_codparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o116_orcparamseqcoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_orcparamseqcoluna"])){ 
       $sql  .= $virgula." o116_orcparamseqcoluna = $this->o116_orcparamseqcoluna ";
       $virgula = ",";
       if(trim($this->o116_orcparamseqcoluna) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o116_orcparamseqcoluna";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o116_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_ordem"])){ 
       $sql  .= $virgula." o116_ordem = $this->o116_ordem ";
       $virgula = ",";
       if(trim($this->o116_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem da coluna nao Informado.";
         $this->erro_campo = "o116_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o116_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"])){ 
        if(trim($this->o116_periodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"])){ 
           $this->o116_periodo = "0" ; 
        } 
       $sql  .= $virgula." o116_periodo = $this->o116_periodo ";
       $virgula = ",";
     }
     if(trim($this->o116_formula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_formula"])){ 
       $sql  .= $virgula." o116_formula = '$this->o116_formula' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o116_sequencial!=null){
       $sql .= " o116_sequencial = $this->o116_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o116_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14123,'$this->o116_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_sequencial"]) || $this->o116_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2484,14123,'".AddSlashes(pg_result($resaco,$conresaco,'o116_sequencial'))."','$this->o116_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_codseq"]) || $this->o116_codseq != "")
           $resac = db_query("insert into db_acount values($acount,2484,14124,'".AddSlashes(pg_result($resaco,$conresaco,'o116_codseq'))."','$this->o116_codseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_codparamrel"]) || $this->o116_codparamrel != "")
           $resac = db_query("insert into db_acount values($acount,2484,14125,'".AddSlashes(pg_result($resaco,$conresaco,'o116_codparamrel'))."','$this->o116_codparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_orcparamseqcoluna"]) || $this->o116_orcparamseqcoluna != "")
           $resac = db_query("insert into db_acount values($acount,2484,14127,'".AddSlashes(pg_result($resaco,$conresaco,'o116_orcparamseqcoluna'))."','$this->o116_orcparamseqcoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_ordem"]) || $this->o116_ordem != "")
           $resac = db_query("insert into db_acount values($acount,2484,14128,'".AddSlashes(pg_result($resaco,$conresaco,'o116_ordem'))."','$this->o116_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"]) || $this->o116_periodo != "")
           $resac = db_query("insert into db_acount values($acount,2484,14126,'".AddSlashes(pg_result($resaco,$conresaco,'o116_periodo'))."','$this->o116_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o116_formula"]) || $this->o116_formula != "")
           $resac = db_query("insert into db_acount values($acount,2484,17724,'".AddSlashes(pg_result($resaco,$conresaco,'o116_formula'))."','$this->o116_formula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Colunas do relatório nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o116_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Colunas do relatório nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o116_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o116_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14123,'$o116_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2484,14123,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,14124,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_codseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,14125,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_codparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,14127,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_orcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,14128,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,14126,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2484,17724,'','".AddSlashes(pg_result($resaco,$iresaco,'o116_formula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqorcparamseqcoluna
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o116_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o116_sequencial = $o116_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Colunas do relatório nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o116_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Colunas do relatório nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o116_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqorcparamseqcoluna";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o116_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqorcparamseqcoluna ";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamseqorcparamseqcoluna.o116_codparamrel and  orcparamseq.o69_codseq = orcparamseqorcparamseqcoluna.o116_codseq";
     $sql .= "      left  join periodo  on  periodo.o114_sequencial = orcparamseqorcparamseqcoluna.o116_periodo";
     $sql .= "      inner join orcparamseqcoluna  on  orcparamseqcoluna.o115_sequencial = orcparamseqorcparamseqcoluna.o116_orcparamseqcoluna";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o116_sequencial!=null ){
         $sql2 .= " where orcparamseqorcparamseqcoluna.o116_sequencial = $o116_sequencial "; 
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
   function sql_query_file ( $o116_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqorcparamseqcoluna ";
     $sql2 = "";
     if($dbwhere==""){
       if($o116_sequencial!=null ){
         $sql2 .= " where orcparamseqorcparamseqcoluna.o116_sequencial = $o116_sequencial "; 
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
  
  /*
   * criada para dar a opção do parametro where na alteração
   */
  function alterar_where ($o116_sequencial = null, $dbwhere = null) {
    
    $this->atualizacampos();
    $sql     = " update orcparamseqorcparamseqcoluna set ";
    $virgula = "";

    if(trim($this->o116_codparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_codparamrel"])){
      $sql  .= $virgula." o116_codparamrel = $this->o116_codparamrel ";
      $virgula = ",";
      if(trim($this->o116_codparamrel) == null ){
        $this->erro_sql = " Campo codigo do relatorio nao Informado.";
        $this->erro_campo = "o116_codparamrel";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->o116_orcparamseqcoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_orcparamseqcoluna"])){
      $sql  .= $virgula." o116_orcparamseqcoluna = $this->o116_orcparamseqcoluna ";
      $virgula = ",";
      if(trim($this->o116_orcparamseqcoluna) == null ){
        $this->erro_sql = " Campo Código Sequencial nao Informado.";
        $this->erro_campo = "o116_orcparamseqcoluna";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->o116_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o116_ordem"])){
      
      $sql  .= $virgula." o116_ordem = $this->o116_ordem ";
      $virgula = ",";
      if(trim($this->o116_ordem) == null ){
        
        $this->erro_sql = " Campo Ordem da coluna nao Informado.";
        $this->erro_campo = "o116_ordem";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o116_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"])) {
      if(trim($this->o116_periodo) == "" && isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"])) {
        $this->o116_periodo = "0" ;
      }
      $sql  .= $virgula." o116_periodo = $this->o116_periodo ";
      $virgula = ",";
    }
    if (trim($this->o116_formula) == "" || isset($GLOBALS["HTTP_POST_VARS"]["o116_formula"])){
      
      $sql  .= $virgula." o116_formula = '$this->o116_formula' ";
      $virgula = ",";
    }
    $sql .= " where ";
    
    if (isset($dbwhere) && $dbwhere != "") {
      $sql .= $dbwhere;
    }else{
      
      if($o116_sequencial != null){
        $sql .= " o116_sequencial = $this->o116_sequencial";
      }
    }
    $sWhere = " o116_sequencial = {$this->o116_sequencial} ";
    if ($this->o116_sequencial == null) {
      $sWhere = $dbwhere;
    }
    $resaco = $this->sql_record($this->sql_query_file (null, "*", null, $sWhere));
    
    if ($this->numrows > 0) {
      
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        
        $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac  = db_query("insert into db_acountkey values($acount,14123,'$this->o116_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_sequencial"]) || $this->o116_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2484,14123,'".AddSlashes(pg_result($resaco,$conresaco,'o116_sequencial'))."','$this->o116_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_codseq"]) || $this->o116_codseq != "")
          $resac = db_query("insert into db_acount values($acount,2484,14124,'".AddSlashes(pg_result($resaco,$conresaco,'o116_codseq'))."','$this->o116_codseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_codparamrel"]) || $this->o116_codparamrel != "")
          $resac = db_query("insert into db_acount values($acount,2484,14125,'".AddSlashes(pg_result($resaco,$conresaco,'o116_codparamrel'))."','$this->o116_codparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_orcparamseqcoluna"]) || $this->o116_orcparamseqcoluna != "")
          $resac = db_query("insert into db_acount values($acount,2484,14127,'".AddSlashes(pg_result($resaco,$conresaco,'o116_orcparamseqcoluna'))."','$this->o116_orcparamseqcoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_ordem"]) || $this->o116_ordem != "")
          $resac = db_query("insert into db_acount values($acount,2484,14128,'".AddSlashes(pg_result($resaco,$conresaco,'o116_ordem'))."','$this->o116_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_periodo"]) || $this->o116_periodo != "")
          $resac = db_query("insert into db_acount values($acount,2484,14126,'".AddSlashes(pg_result($resaco,$conresaco,'o116_periodo'))."','$this->o116_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["o116_formula"]) || $this->o116_formula != "")
          $resac = db_query("insert into db_acount values($acount,2484,17724,'".AddSlashes(pg_result($resaco,$conresaco,'o116_formula'))."','$this->o116_formula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      
      $this->erro_banco  = str_replace("\n","",@pg_last_error());
      $this->erro_sql    = "Colunas do relatório nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql   .= "Valores : ".$this->o116_sequencial;
      $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
      
    } else {
      
      if (pg_affected_rows($result) == 0) {
        
        $this->erro_banco      = "";
        $this->erro_sql        = "Colunas do relatório nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql       .= "Valores : ".$this->o116_sequencial;
        $this->erro_msg        = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg       .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status     = "1";
        $this->numrows_alterar = 0;
        return true;
        
      } else {
        
        $this->erro_banco      = "";
        $this->erro_sql        = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql       .= "Valores : ".$this->o116_sequencial;
        $this->erro_msg        = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg       .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status     = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }  
}
?>