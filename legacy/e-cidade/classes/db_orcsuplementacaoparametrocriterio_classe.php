<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE orcsuplementacaoparametrocriterio
class cl_orcsuplementacaoparametrocriterio { 
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
   var $o135_sequencial = 0; 
   var $o135_orcsuplementacaoparametro = 0; 
   var $o135_descricao = null; 
   var $o135_nivel = 0; 
   var $o135_valor = null; 
   var $o135_fundamentacaolegal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o135_sequencial = int4 = C�digo Sequencial 
                 o135_orcsuplementacaoparametro = int4 = Ano do Par�metro 
                 o135_descricao = varchar(50) = Descri��o 
                 o135_nivel = int4 = N�vel do Crit�rio 
                 o135_valor = varchar(50) = Valor do Crit�rio 
                 o135_fundamentacaolegal = text = Fundamenta��o Legal do Crit�rio 
                 ";
   //funcao construtor da classe 
   function cl_orcsuplementacaoparametrocriterio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplementacaoparametrocriterio"); 
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
       $this->o135_sequencial = ($this->o135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_sequencial"]:$this->o135_sequencial);
       $this->o135_orcsuplementacaoparametro = ($this->o135_orcsuplementacaoparametro == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_orcsuplementacaoparametro"]:$this->o135_orcsuplementacaoparametro);
       $this->o135_descricao = ($this->o135_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_descricao"]:$this->o135_descricao);
       $this->o135_nivel = ($this->o135_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_nivel"]:$this->o135_nivel);
       $this->o135_valor = ($this->o135_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_valor"]:$this->o135_valor);
       $this->o135_fundamentacaolegal = ($this->o135_fundamentacaolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_fundamentacaolegal"]:$this->o135_fundamentacaolegal);
     }else{
       $this->o135_sequencial = ($this->o135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o135_sequencial"]:$this->o135_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o135_sequencial){ 
      $this->atualizacampos();
     if($this->o135_orcsuplementacaoparametro == null ){ 
       $this->erro_sql = " Campo Ano do Par�metro nao Informado.";
       $this->erro_campo = "o135_orcsuplementacaoparametro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o135_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "o135_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o135_nivel == null ){ 
       $this->erro_sql = " Campo N�vel do Crit�rio nao Informado.";
       $this->erro_campo = "o135_nivel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o135_sequencial == "" || $o135_sequencial == null ){
       $result = db_query("select nextval('orcsuplementacaoparametrocriterio_o135_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcsuplementacaoparametrocriterio_o135_sequencial_seq do campo: o135_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o135_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcsuplementacaoparametrocriterio_o135_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o135_sequencial)){
         $this->erro_sql = " Campo o135_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o135_sequencial = $o135_sequencial; 
       }
     }
     if(($this->o135_sequencial == null) || ($this->o135_sequencial == "") ){ 
       $this->erro_sql = " Campo o135_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplementacaoparametrocriterio(
                                       o135_sequencial 
                                      ,o135_orcsuplementacaoparametro 
                                      ,o135_descricao 
                                      ,o135_nivel 
                                      ,o135_valor 
                                      ,o135_fundamentacaolegal 
                       )
                values (
                                $this->o135_sequencial 
                               ,$this->o135_orcsuplementacaoparametro 
                               ,'$this->o135_descricao' 
                               ,$this->o135_nivel 
                               ,'$this->o135_valor' 
                               ,'$this->o135_fundamentacaolegal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "orcsuplementacaoparametrocriterio ($this->o135_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "orcsuplementacaoparametrocriterio j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "orcsuplementacaoparametrocriterio ($this->o135_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o135_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o135_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17660,'$this->o135_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3119,17660,'','".AddSlashes(pg_result($resaco,0,'o135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3119,17661,'','".AddSlashes(pg_result($resaco,0,'o135_orcsuplementacaoparametro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3119,17662,'','".AddSlashes(pg_result($resaco,0,'o135_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3119,17663,'','".AddSlashes(pg_result($resaco,0,'o135_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3119,17664,'','".AddSlashes(pg_result($resaco,0,'o135_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3119,17665,'','".AddSlashes(pg_result($resaco,0,'o135_fundamentacaolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o135_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcsuplementacaoparametrocriterio set ";
     $virgula = "";
     if(trim($this->o135_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_sequencial"])){ 
       $sql  .= $virgula." o135_sequencial = $this->o135_sequencial ";
       $virgula = ",";
       if(trim($this->o135_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "o135_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o135_orcsuplementacaoparametro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_orcsuplementacaoparametro"])){ 
       $sql  .= $virgula." o135_orcsuplementacaoparametro = $this->o135_orcsuplementacaoparametro ";
       $virgula = ",";
       if(trim($this->o135_orcsuplementacaoparametro) == null ){ 
         $this->erro_sql = " Campo Ano do Par�metro nao Informado.";
         $this->erro_campo = "o135_orcsuplementacaoparametro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o135_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_descricao"])){ 
       $sql  .= $virgula." o135_descricao = '$this->o135_descricao' ";
       $virgula = ",";
       if(trim($this->o135_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "o135_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o135_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_nivel"])){ 
       $sql  .= $virgula." o135_nivel = $this->o135_nivel ";
       $virgula = ",";
       if(trim($this->o135_nivel) == null ){ 
         $this->erro_sql = " Campo N�vel do Crit�rio nao Informado.";
         $this->erro_campo = "o135_nivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o135_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_valor"])){ 
       $sql  .= $virgula." o135_valor = '$this->o135_valor' ";
       $virgula = ",";
     }
     if(trim($this->o135_fundamentacaolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o135_fundamentacaolegal"])){ 
       $sql  .= $virgula." o135_fundamentacaolegal = '$this->o135_fundamentacaolegal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o135_sequencial!=null){
       $sql .= " o135_sequencial = $this->o135_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o135_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17660,'$this->o135_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_sequencial"]) || $this->o135_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3119,17660,'".AddSlashes(pg_result($resaco,$conresaco,'o135_sequencial'))."','$this->o135_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_orcsuplementacaoparametro"]) || $this->o135_orcsuplementacaoparametro != "")
           $resac = db_query("insert into db_acount values($acount,3119,17661,'".AddSlashes(pg_result($resaco,$conresaco,'o135_orcsuplementacaoparametro'))."','$this->o135_orcsuplementacaoparametro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_descricao"]) || $this->o135_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3119,17662,'".AddSlashes(pg_result($resaco,$conresaco,'o135_descricao'))."','$this->o135_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_nivel"]) || $this->o135_nivel != "")
           $resac = db_query("insert into db_acount values($acount,3119,17663,'".AddSlashes(pg_result($resaco,$conresaco,'o135_nivel'))."','$this->o135_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_valor"]) || $this->o135_valor != "")
           $resac = db_query("insert into db_acount values($acount,3119,17664,'".AddSlashes(pg_result($resaco,$conresaco,'o135_valor'))."','$this->o135_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o135_fundamentacaolegal"]) || $this->o135_fundamentacaolegal != "")
           $resac = db_query("insert into db_acount values($acount,3119,17665,'".AddSlashes(pg_result($resaco,$conresaco,'o135_fundamentacaolegal'))."','$this->o135_fundamentacaolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcsuplementacaoparametrocriterio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o135_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcsuplementacaoparametrocriterio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o135_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o135_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o135_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o135_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17660,'$o135_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3119,17660,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3119,17661,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_orcsuplementacaoparametro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3119,17662,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3119,17663,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3119,17664,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3119,17665,'','".AddSlashes(pg_result($resaco,$iresaco,'o135_fundamentacaolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplementacaoparametrocriterio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o135_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o135_sequencial = $o135_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcsuplementacaoparametrocriterio nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o135_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcsuplementacaoparametrocriterio nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o135_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o135_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplementacaoparametrocriterio";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o135_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplementacaoparametrocriterio ";
     $sql .= "      inner join orcsuplementacaoparametro  on  orcsuplementacaoparametro.o134_anousu = orcsuplementacaoparametrocriterio.o135_orcsuplementacaoparametro";
     $sql2 = "";
     if($dbwhere==""){
       if($o135_sequencial!=null ){
         $sql2 .= " where orcsuplementacaoparametrocriterio.o135_sequencial = $o135_sequencial "; 
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
   function sql_query_file ( $o135_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplementacaoparametrocriterio ";
     $sql2 = "";
     if($dbwhere==""){
       if($o135_sequencial!=null ){
         $sql2 .= " where orcsuplementacaoparametrocriterio.o135_sequencial = $o135_sequencial "; 
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