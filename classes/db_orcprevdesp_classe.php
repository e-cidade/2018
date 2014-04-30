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
//CLASSE DA ENTIDADE orcprevdesp
class cl_orcprevdesp { 
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
   var $o35_anousu = 0; 
   var $o35_projativ = 0; 
   var $o35_codigo = 0; 
   var $o35_mes = 0; 
   var $o35_perc = 0; 
   var $o35_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o35_anousu = int4 = Exercício 
                 o35_projativ = int4 = Projetos / Atividades 
                 o35_codigo = int4 = Recurso 
                 o35_mes = int4 = Mês 
                 o35_perc = float8 = Percentual 
                 o35_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcprevdesp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprevdesp"); 
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
       $this->o35_anousu = ($this->o35_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_anousu"]:$this->o35_anousu);
       $this->o35_projativ = ($this->o35_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_projativ"]:$this->o35_projativ);
       $this->o35_codigo = ($this->o35_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_codigo"]:$this->o35_codigo);
       $this->o35_mes = ($this->o35_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_mes"]:$this->o35_mes);
       $this->o35_perc = ($this->o35_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_perc"]:$this->o35_perc);
       $this->o35_valor = ($this->o35_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_valor"]:$this->o35_valor);
     }else{
       $this->o35_anousu = ($this->o35_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_anousu"]:$this->o35_anousu);
       $this->o35_projativ = ($this->o35_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_projativ"]:$this->o35_projativ);
       $this->o35_codigo = ($this->o35_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_codigo"]:$this->o35_codigo);
       $this->o35_mes = ($this->o35_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o35_mes"]:$this->o35_mes);
     }
   }
   // funcao para inclusao
   function incluir ($o35_anousu,$o35_projativ,$o35_codigo,$o35_mes){ 
      $this->atualizacampos();
     if($this->o35_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "o35_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o35_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o35_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o35_anousu = $o35_anousu; 
       $this->o35_projativ = $o35_projativ; 
       $this->o35_codigo = $o35_codigo; 
       $this->o35_mes = $o35_mes; 
     if(($this->o35_anousu == null) || ($this->o35_anousu == "") ){ 
       $this->erro_sql = " Campo o35_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o35_projativ == null) || ($this->o35_projativ == "") ){ 
       $this->erro_sql = " Campo o35_projativ nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o35_codigo == null) || ($this->o35_codigo == "") ){ 
       $this->erro_sql = " Campo o35_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o35_mes == null) || ($this->o35_mes == "") ){ 
       $this->erro_sql = " Campo o35_mes nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprevdesp(
                                       o35_anousu 
                                      ,o35_projativ 
                                      ,o35_codigo 
                                      ,o35_mes 
                                      ,o35_perc 
                                      ,o35_valor 
                       )
                values (
                                $this->o35_anousu 
                               ,$this->o35_projativ 
                               ,$this->o35_codigo 
                               ,$this->o35_mes 
                               ,$this->o35_perc 
                               ,$this->o35_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Previsão da despesa ($this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Previsão da despesa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Previsão da despesa ($this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o35_anousu,$this->o35_projativ,$this->o35_codigo,$this->o35_mes));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8691,'$this->o35_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,8692,'$this->o35_projativ','I')");
       $resac = db_query("insert into db_acountkey values($acount,8693,'$this->o35_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,8694,'$this->o35_mes','I')");
       $resac = db_query("insert into db_acount values($acount,1483,8691,'','".AddSlashes(pg_result($resaco,0,'o35_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1483,8692,'','".AddSlashes(pg_result($resaco,0,'o35_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1483,8693,'','".AddSlashes(pg_result($resaco,0,'o35_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1483,8694,'','".AddSlashes(pg_result($resaco,0,'o35_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1483,8695,'','".AddSlashes(pg_result($resaco,0,'o35_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1483,8696,'','".AddSlashes(pg_result($resaco,0,'o35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o35_anousu=null,$o35_projativ=null,$o35_codigo=null,$o35_mes=null) { 
      $this->atualizacampos();
     $sql = " update orcprevdesp set ";
     $virgula = "";
     if(trim($this->o35_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_anousu"])){ 
       $sql  .= $virgula." o35_anousu = $this->o35_anousu ";
       $virgula = ",";
       if(trim($this->o35_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o35_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o35_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_projativ"])){ 
       $sql  .= $virgula." o35_projativ = $this->o35_projativ ";
       $virgula = ",";
       if(trim($this->o35_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o35_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o35_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_codigo"])){ 
       $sql  .= $virgula." o35_codigo = $this->o35_codigo ";
       $virgula = ",";
       if(trim($this->o35_codigo) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "o35_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o35_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_mes"])){ 
       $sql  .= $virgula." o35_mes = $this->o35_mes ";
       $virgula = ",";
       if(trim($this->o35_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "o35_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o35_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_perc"])){ 
       $sql  .= $virgula." o35_perc = $this->o35_perc ";
       $virgula = ",";
       if(trim($this->o35_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "o35_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o35_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o35_valor"])){ 
       $sql  .= $virgula." o35_valor = $this->o35_valor ";
       $virgula = ",";
       if(trim($this->o35_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o35_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o35_anousu!=null){
       $sql .= " o35_anousu = $this->o35_anousu";
     }
     if($o35_projativ!=null){
       $sql .= " and  o35_projativ = $this->o35_projativ";
     }
     if($o35_codigo!=null){
       $sql .= " and  o35_codigo = $this->o35_codigo";
     }
     if($o35_mes!=null){
       $sql .= " and  o35_mes = $this->o35_mes";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o35_anousu,$this->o35_projativ,$this->o35_codigo,$this->o35_mes));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8691,'$this->o35_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,8692,'$this->o35_projativ','A')");
         $resac = db_query("insert into db_acountkey values($acount,8693,'$this->o35_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,8694,'$this->o35_mes','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1483,8691,'".AddSlashes(pg_result($resaco,$conresaco,'o35_anousu'))."','$this->o35_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_projativ"]))
           $resac = db_query("insert into db_acount values($acount,1483,8692,'".AddSlashes(pg_result($resaco,$conresaco,'o35_projativ'))."','$this->o35_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1483,8693,'".AddSlashes(pg_result($resaco,$conresaco,'o35_codigo'))."','$this->o35_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_mes"]))
           $resac = db_query("insert into db_acount values($acount,1483,8694,'".AddSlashes(pg_result($resaco,$conresaco,'o35_mes'))."','$this->o35_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_perc"]))
           $resac = db_query("insert into db_acount values($acount,1483,8695,'".AddSlashes(pg_result($resaco,$conresaco,'o35_perc'))."','$this->o35_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o35_valor"]))
           $resac = db_query("insert into db_acount values($acount,1483,8696,'".AddSlashes(pg_result($resaco,$conresaco,'o35_valor'))."','$this->o35_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previsão da despesa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Previsão da despesa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o35_anousu."-".$this->o35_projativ."-".$this->o35_codigo."-".$this->o35_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o35_anousu=null,$o35_projativ=null,$o35_codigo=null,$o35_mes=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o35_anousu,$o35_projativ,$o35_codigo,$o35_mes));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8691,'$o35_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,8692,'$o35_projativ','E')");
         $resac = db_query("insert into db_acountkey values($acount,8693,'$o35_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,8694,'$o35_mes','E')");
         $resac = db_query("insert into db_acount values($acount,1483,8691,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1483,8692,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1483,8693,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1483,8694,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1483,8695,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1483,8696,'','".AddSlashes(pg_result($resaco,$iresaco,'o35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprevdesp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o35_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o35_anousu = $o35_anousu ";
        }
        if($o35_projativ != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o35_projativ = $o35_projativ ";
        }
        if($o35_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o35_codigo = $o35_codigo ";
        }
        if($o35_mes != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o35_mes = $o35_mes ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previsão da despesa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o35_anousu."-".$o35_projativ."-".$o35_codigo."-".$o35_mes;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Previsão da despesa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o35_anousu."-".$o35_projativ."-".$o35_codigo."-".$o35_mes;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o35_anousu."-".$o35_projativ."-".$o35_codigo."-".$o35_mes;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprevdesp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o35_anousu=null,$o35_projativ=null,$o35_codigo=null,$o35_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprevdesp ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcprevdesp.o35_codigo";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcprevdesp.o35_anousu and  orcprojativ.o55_projativ = orcprevdesp.o35_projativ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = orcprojativ.o55_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($o35_anousu!=null ){
         $sql2 .= " where orcprevdesp.o35_anousu = $o35_anousu "; 
       } 
       if($o35_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_projativ = $o35_projativ "; 
       } 
       if($o35_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_codigo = $o35_codigo "; 
       } 
       if($o35_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_mes = $o35_mes "; 
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
   function sql_query_file ( $o35_anousu=null,$o35_projativ=null,$o35_codigo=null,$o35_mes=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprevdesp ";
     $sql2 = "";
     if($dbwhere==""){
       if($o35_anousu!=null ){
         $sql2 .= " where orcprevdesp.o35_anousu = $o35_anousu "; 
       } 
       if($o35_projativ!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_projativ = $o35_projativ "; 
       } 
       if($o35_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_codigo = $o35_codigo "; 
       } 
       if($o35_mes!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprevdesp.o35_mes = $o35_mes "; 
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