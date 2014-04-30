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

//MODULO: pessoal
//CLASSE DA ENTIDADE desconto
class cl_desconto { 
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
   var $r27_anousu = 0; 
   var $r27_mesusu = 0; 
   var $r27_codigo = null; 
   var $r27_descr = null; 
   var $r27_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r27_anousu = int4 = Ano do Exercicio 
                 r27_mesusu = int4 = Mes do Exercicio 
                 r27_codigo = varchar(4) = Código do Desconto 
                 r27_descr = varchar(30) = Descrição do Desconto 
                 r27_valor = float8 = Valor do Desconto 
                 ";
   //funcao construtor da classe 
   function cl_desconto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("desconto"); 
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
       $this->r27_anousu = ($this->r27_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_anousu"]:$this->r27_anousu);
       $this->r27_mesusu = ($this->r27_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_mesusu"]:$this->r27_mesusu);
       $this->r27_codigo = ($this->r27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_codigo"]:$this->r27_codigo);
       $this->r27_descr = ($this->r27_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_descr"]:$this->r27_descr);
       $this->r27_valor = ($this->r27_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_valor"]:$this->r27_valor);
     }else{
       $this->r27_anousu = ($this->r27_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_anousu"]:$this->r27_anousu);
       $this->r27_mesusu = ($this->r27_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_mesusu"]:$this->r27_mesusu);
       $this->r27_codigo = ($this->r27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r27_codigo"]:$this->r27_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r27_anousu,$r27_mesusu,$r27_codigo){ 
      $this->atualizacampos();
     if($this->r27_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Desconto nao Informado.";
       $this->erro_campo = "r27_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r27_valor == null ){ 
       $this->erro_sql = " Campo Valor do Desconto nao Informado.";
       $this->erro_campo = "r27_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r27_anousu = $r27_anousu; 
       $this->r27_mesusu = $r27_mesusu; 
       $this->r27_codigo = $r27_codigo; 
     if(($this->r27_anousu == null) || ($this->r27_anousu == "") ){ 
       $this->erro_sql = " Campo r27_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r27_mesusu == null) || ($this->r27_mesusu == "") ){ 
       $this->erro_sql = " Campo r27_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r27_codigo == null) || ($this->r27_codigo == "") ){ 
       $this->erro_sql = " Campo r27_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into desconto(
                                       r27_anousu 
                                      ,r27_mesusu 
                                      ,r27_codigo 
                                      ,r27_descr 
                                      ,r27_valor 
                       )
                values (
                                $this->r27_anousu 
                               ,$this->r27_mesusu 
                               ,'$this->r27_codigo' 
                               ,'$this->r27_descr' 
                               ,$this->r27_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos Descontos ($this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos Descontos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos Descontos ($this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r27_anousu,$this->r27_mesusu,$this->r27_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3880,'$this->r27_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3881,'$this->r27_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3882,'$this->r27_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,545,3880,'','".AddSlashes(pg_result($resaco,0,'r27_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,545,3881,'','".AddSlashes(pg_result($resaco,0,'r27_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,545,3882,'','".AddSlashes(pg_result($resaco,0,'r27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,545,3883,'','".AddSlashes(pg_result($resaco,0,'r27_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,545,3884,'','".AddSlashes(pg_result($resaco,0,'r27_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r27_anousu=null,$r27_mesusu=null,$r27_codigo=null) { 
      $this->atualizacampos();
     $sql = " update desconto set ";
     $virgula = "";
     if(trim($this->r27_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r27_anousu"])){ 
       $sql  .= $virgula." r27_anousu = $this->r27_anousu ";
       $virgula = ",";
       if(trim($this->r27_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r27_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r27_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r27_mesusu"])){ 
       $sql  .= $virgula." r27_mesusu = $this->r27_mesusu ";
       $virgula = ",";
       if(trim($this->r27_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r27_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r27_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r27_codigo"])){ 
       $sql  .= $virgula." r27_codigo = '$this->r27_codigo' ";
       $virgula = ",";
       if(trim($this->r27_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Desconto nao Informado.";
         $this->erro_campo = "r27_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r27_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r27_descr"])){ 
       $sql  .= $virgula." r27_descr = '$this->r27_descr' ";
       $virgula = ",";
       if(trim($this->r27_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Desconto nao Informado.";
         $this->erro_campo = "r27_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r27_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r27_valor"])){ 
       $sql  .= $virgula." r27_valor = $this->r27_valor ";
       $virgula = ",";
       if(trim($this->r27_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Desconto nao Informado.";
         $this->erro_campo = "r27_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r27_anousu!=null){
       $sql .= " r27_anousu = $this->r27_anousu";
     }
     if($r27_mesusu!=null){
       $sql .= " and  r27_mesusu = $this->r27_mesusu";
     }
     if($r27_codigo!=null){
       $sql .= " and  r27_codigo = '$this->r27_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r27_anousu,$this->r27_mesusu,$this->r27_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3880,'$this->r27_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3881,'$this->r27_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3882,'$this->r27_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r27_anousu"]))
           $resac = db_query("insert into db_acount values($acount,545,3880,'".AddSlashes(pg_result($resaco,$conresaco,'r27_anousu'))."','$this->r27_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r27_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,545,3881,'".AddSlashes(pg_result($resaco,$conresaco,'r27_mesusu'))."','$this->r27_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r27_codigo"]))
           $resac = db_query("insert into db_acount values($acount,545,3882,'".AddSlashes(pg_result($resaco,$conresaco,'r27_codigo'))."','$this->r27_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r27_descr"]))
           $resac = db_query("insert into db_acount values($acount,545,3883,'".AddSlashes(pg_result($resaco,$conresaco,'r27_descr'))."','$this->r27_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r27_valor"]))
           $resac = db_query("insert into db_acount values($acount,545,3884,'".AddSlashes(pg_result($resaco,$conresaco,'r27_valor'))."','$this->r27_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Descontos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Descontos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r27_anousu."-".$this->r27_mesusu."-".$this->r27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r27_anousu=null,$r27_mesusu=null,$r27_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r27_anousu,$r27_mesusu,$r27_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3880,'$r27_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3881,'$r27_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3882,'$r27_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,545,3880,'','".AddSlashes(pg_result($resaco,$iresaco,'r27_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,545,3881,'','".AddSlashes(pg_result($resaco,$iresaco,'r27_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,545,3882,'','".AddSlashes(pg_result($resaco,$iresaco,'r27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,545,3883,'','".AddSlashes(pg_result($resaco,$iresaco,'r27_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,545,3884,'','".AddSlashes(pg_result($resaco,$iresaco,'r27_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from desconto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r27_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r27_anousu = $r27_anousu ";
        }
        if($r27_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r27_mesusu = $r27_mesusu ";
        }
        if($r27_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r27_codigo = '$r27_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos Descontos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r27_anousu."-".$r27_mesusu."-".$r27_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos Descontos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r27_anousu."-".$r27_mesusu."-".$r27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r27_anousu."-".$r27_mesusu."-".$r27_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:desconto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r27_anousu,$this->r27_mesusu,$this->r27_codigo);
   }
   function sql_query ( $r27_anousu=null,$r27_mesusu=null,$r27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from desconto ";
     $sql2 = "";
     if($dbwhere==""){
       if($r27_anousu!=null ){
         $sql2 .= " where desconto.r27_anousu = $r27_anousu "; 
       } 
       if($r27_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " desconto.r27_mesusu = $r27_mesusu "; 
       } 
       if($r27_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " desconto.r27_codigo = '$r27_codigo' "; 
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
   function sql_query_file ( $r27_anousu=null,$r27_mesusu=null,$r27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from desconto ";
     $sql2 = "";
     if($dbwhere==""){
       if($r27_anousu!=null ){
         $sql2 .= " where desconto.r27_anousu = $r27_anousu "; 
       } 
       if($r27_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " desconto.r27_mesusu = $r27_mesusu "; 
       } 
       if($r27_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " desconto.r27_codigo = '$r27_codigo' "; 
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