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
//CLASSE DA ENTIDADE orcreserprev
class cl_orcreserprev { 
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
   var $o33_anousu = 0; 
   var $o33_projativ = 0; 
   var $o33_codigo = 0; 
   var $o33_mes = 0; 
   var $o33_perc = 0; 
   var $o33_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o33_anousu = int4 = Exercício 
                 o33_projativ = int4 = Projetos / Atividades 
                 o33_codigo = int4 = Recurso 
                 o33_mes = int4 = Mês 
                 o33_perc = float8 = Percentual 
                 o33_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcreserprev() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcreserprev"); 
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
       $this->o33_anousu = ($this->o33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_anousu"]:$this->o33_anousu);
       $this->o33_projativ = ($this->o33_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_projativ"]:$this->o33_projativ);
       $this->o33_codigo = ($this->o33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_codigo"]:$this->o33_codigo);
       $this->o33_mes = ($this->o33_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_mes"]:$this->o33_mes);
       $this->o33_perc = ($this->o33_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_perc"]:$this->o33_perc);
       $this->o33_valor = ($this->o33_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_valor"]:$this->o33_valor);
     }else{
       $this->o33_anousu = ($this->o33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_anousu"]:$this->o33_anousu);
       $this->o33_projativ = ($this->o33_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_projativ"]:$this->o33_projativ);
       $this->o33_codigo = ($this->o33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_codigo"]:$this->o33_codigo);
       $this->o33_mes = ($this->o33_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o33_mes"]:$this->o33_mes);
     }
   }
   // funcao para inclusao
   function incluir ($o33_anousu,$o33_projativ,$o33_codigo,$o33_mes){ 
      $this->atualizacampos();
     if($this->o33_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "o33_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o33_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o33_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o33_anousu = $o33_anousu; 
       $this->o33_projativ = $o33_projativ; 
       $this->o33_codigo = $o33_codigo; 
       $this->o33_mes = $o33_mes; 
     if(($this->o33_anousu == null) || ($this->o33_anousu == "") ){ 
       $this->erro_sql = " Campo o33_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o33_projativ == null) || ($this->o33_projativ == "") ){ 
       $this->erro_sql = " Campo o33_projativ nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o33_codigo == null) || ($this->o33_codigo == "") ){ 
       $this->erro_sql = " Campo o33_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o33_mes == null) || ($this->o33_mes == "") ){ 
       $this->erro_sql = " Campo o33_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcreserprev(
                                       o33_anousu 
                                      ,o33_projativ 
                                      ,o33_codigo 
                                      ,o33_mes 
                                      ,o33_perc 
                                      ,o33_valor 
                       )
                values (
                                $this->o33_anousu 
                               ,$this->o33_projativ 
                               ,$this->o33_codigo 
                               ,$this->o33_mes 
                               ,$this->o33_perc 
                               ,$this->o33_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Previsão de bloqueio de verba ($this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Previsão de bloqueio de verba já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Previsão de bloqueio de verba ($this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o33_anousu,$this->o33_projativ,$this->o33_codigo,$this->o33_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8022,'$this->o33_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,8023,'$this->o33_projativ','I')");
       $resac = db_query("insert into db_acountkey values($acount,8024,'$this->o33_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,8025,'$this->o33_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1353,8022,'','".AddSlashes(pg_result($resaco,0,'o33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1353,8023,'','".AddSlashes(pg_result($resaco,0,'o33_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1353,8024,'','".AddSlashes(pg_result($resaco,0,'o33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1353,8025,'','".AddSlashes(pg_result($resaco,0,'o33_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1353,8026,'','".AddSlashes(pg_result($resaco,0,'o33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1353,8027,'','".AddSlashes(pg_result($resaco,0,'o33_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o33_anousu=null,$o33_projativ=null,$o33_codigo=null,$o33_mes=null) { 
      $this->atualizacampos();
     $sql = " update orcreserprev set ";
     $virgula = "";
     if(trim($this->o33_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_anousu"])){ 
       $sql  .= $virgula." o33_anousu = $this->o33_anousu ";
       $virgula = ",";
       if(trim($this->o33_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o33_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o33_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_projativ"])){ 
       $sql  .= $virgula." o33_projativ = $this->o33_projativ ";
       $virgula = ",";
       if(trim($this->o33_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o33_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o33_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_codigo"])){ 
       $sql  .= $virgula." o33_codigo = $this->o33_codigo ";
       $virgula = ",";
       if(trim($this->o33_codigo) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "o33_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o33_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_mes"])){ 
       $sql  .= $virgula." o33_mes = $this->o33_mes ";
       $virgula = ",";
       if(trim($this->o33_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o33_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o33_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_perc"])){ 
       $sql  .= $virgula." o33_perc = $this->o33_perc ";
       $virgula = ",";
       if(trim($this->o33_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "o33_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o33_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o33_valor"])){ 
       $sql  .= $virgula." o33_valor = $this->o33_valor ";
       $virgula = ",";
       if(trim($this->o33_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o33_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o33_anousu!=null){
       $sql .= " o33_anousu = $this->o33_anousu";
     }
     if($o33_projativ!=null){
       $sql .= " and  o33_projativ = $this->o33_projativ";
     }
     if($o33_codigo!=null){
       $sql .= " and  o33_codigo = $this->o33_codigo";
     }
     if($o33_mes!=null){
       $sql .= " and  o33_mes = $this->o33_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o33_anousu,$this->o33_projativ,$this->o33_codigo,$this->o33_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8022,'$this->o33_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,8023,'$this->o33_projativ','A')");
         $resac = db_query("insert into db_acountkey values($acount,8024,'$this->o33_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,8025,'$this->o33_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1353,8022,'".AddSlashes(pg_result($resaco,$conresaco,'o33_anousu'))."','$this->o33_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_projativ"]))
           $resac = db_query("insert into db_acount values($acount,1353,8023,'".AddSlashes(pg_result($resaco,$conresaco,'o33_projativ'))."','$this->o33_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1353,8024,'".AddSlashes(pg_result($resaco,$conresaco,'o33_codigo'))."','$this->o33_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_mes"]))
           $resac = db_query("insert into db_acount values($acount,1353,8025,'".AddSlashes(pg_result($resaco,$conresaco,'o33_mes'))."','$this->o33_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_perc"]))
           $resac = db_query("insert into db_acount values($acount,1353,8026,'".AddSlashes(pg_result($resaco,$conresaco,'o33_perc'))."','$this->o33_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o33_valor"]))
           $resac = db_query("insert into db_acount values($acount,1353,8027,'".AddSlashes(pg_result($resaco,$conresaco,'o33_valor'))."','$this->o33_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previsão de bloqueio de verba nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Previsão de bloqueio de verba nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o33_anousu."-".$this->o33_projativ."-".$this->o33_codigo."-".$this->o33_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o33_anousu=null,$o33_projativ=null,$o33_codigo=null,$o33_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o33_anousu,$o33_projativ,$o33_codigo,$o33_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8022,'$o33_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,8023,'$o33_projativ','E')");
         $resac = db_query("insert into db_acountkey values($acount,8024,'$o33_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,8025,'$o33_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1353,8022,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1353,8023,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1353,8024,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1353,8025,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1353,8026,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1353,8027,'','".AddSlashes(pg_result($resaco,$iresaco,'o33_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcreserprev
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o33_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o33_anousu = $o33_anousu ";
        }
        if($o33_projativ != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o33_projativ = $o33_projativ ";
        }
        if($o33_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o33_codigo = $o33_codigo ";
        }
        if($o33_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o33_mes = $o33_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previsão de bloqueio de verba nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o33_anousu."-".$o33_projativ."-".$o33_codigo."-".$o33_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Previsão de bloqueio de verba nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o33_anousu."-".$o33_projativ."-".$o33_codigo."-".$o33_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o33_anousu."-".$o33_projativ."-".$o33_codigo."-".$o33_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcreserprev";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o33_anousu=null,$o33_projativ=null,$o33_codigo=null,$o33_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreserprev ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreserprev.o33_codigo";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcreserprev.o33_anousu and  orcprojativ.o55_projativ = orcreserprev.o33_projativ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = orcprojativ.o55_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($o33_anousu!=null ){
         $sql2 .= " where orcreserprev.o33_anousu = $o33_anousu "; 
       } 
       if($o33_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_projativ = $o33_projativ "; 
       } 
       if($o33_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_codigo = $o33_codigo "; 
       } 
       if($o33_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_mes = $o33_mes "; 
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
   function sql_query_file ( $o33_anousu=null,$o33_projativ=null,$o33_codigo=null,$o33_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreserprev ";
     $sql2 = "";
     if($dbwhere==""){
       if($o33_anousu!=null ){
         $sql2 .= " where orcreserprev.o33_anousu = $o33_anousu "; 
       } 
       if($o33_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_projativ = $o33_projativ "; 
       } 
       if($o33_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_codigo = $o33_codigo "; 
       } 
       if($o33_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcreserprev.o33_mes = $o33_mes "; 
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
   function sql_reserva_prev($comsaldo=false,$projativ=0,$recurso=0){
  	
  	$sql = "select o58_coddot,o58_projativ,o55_descr,o58_codigo,o15_descr,sum(o58_valor) as o58_valor,
                   sum(substr(fc_dotacaosaldo,107,12)::float8) as atual,
                   sum(substr(fc_dotacaosaldo,120,12)::float8) as reservado,
                   sum(substr(fc_dotacaosaldo,133,12)::float8) as atual_menos_reservado
            from orcdotacao
                 inner join orcprojativ on o58_anousu = o55_anousu and o58_projativ = o55_projativ
                 inner join orctiporec on o15_codigo =o58_codigo
                 inner join ( select o58_anousu as anousu, o58_coddot as coddot, fc_dotacaosaldo(".db_getsession("DB_anousu").",o58_coddot,5,'".date("Y-m-d",db_getsession("DB_datausu"))."','".db_getsession("DB_anousu")."-12-31') from orcdotacao where o58_anousu = ".db_getsession("DB_anousu")." ) as saldo
                     on saldo.anousu = o58_anousu and saldo.coddot = o58_coddot
            where o58_anousu = ".db_getsession("DB_anousu")." 
                  and o58_instit = ".db_getsession("DB_instit");
    if($projativ > 0){
      $sql .= " and o58_projativ = $projativ ";
    }
    if($recurso > 0){
      $sql .= " and o58_codigo = $recurso ";    
    }
    
    
    $sql .= " 
             group by o58_projativ,o58_codigo,o55_descr,o15_descr,o58_coddot";

    if($comsaldo ==true){
      $sql = " select * from ($sql) as x where atual_menos_reservado > 0 ";    
    }

  	$result = pg_query($sql);

  	return $result;

  }
}
?>