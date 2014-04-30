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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_dae
class cl_db_dae { 
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
   var $w04_codigo = 0; 
   var $w04_inscr = 0; 
   var $w04_enviado = 'f'; 
   var $w04_ano = null; 
   var $w04_data_dia = null; 
   var $w04_data_mes = null; 
   var $w04_data_ano = null; 
   var $w04_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w04_codigo = int4 = Código do dae 
                 w04_inscr = int4 = Inscrição consultada 
                 w04_enviado = bool = Enviado 
                 w04_ano = varchar(4) = Ano 
                 w04_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_db_dae() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_dae"); 
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
       $this->w04_codigo = ($this->w04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_codigo"]:$this->w04_codigo);
       $this->w04_inscr = ($this->w04_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_inscr"]:$this->w04_inscr);
       $this->w04_enviado = ($this->w04_enviado == "f"?@$GLOBALS["HTTP_POST_VARS"]["w04_enviado"]:$this->w04_enviado);
       $this->w04_ano = ($this->w04_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_ano"]:$this->w04_ano);
       if($this->w04_data == ""){
         $this->w04_data_dia = ($this->w04_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_data_dia"]:$this->w04_data_dia);
         $this->w04_data_mes = ($this->w04_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_data_mes"]:$this->w04_data_mes);
         $this->w04_data_ano = ($this->w04_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_data_ano"]:$this->w04_data_ano);
         if($this->w04_data_dia != ""){
            $this->w04_data = $this->w04_data_ano."-".$this->w04_data_mes."-".$this->w04_data_dia;
         }
       }
     }else{
       $this->w04_codigo = ($this->w04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["w04_codigo"]:$this->w04_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($w04_codigo){ 
      $this->atualizacampos();
     if($this->w04_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição consultada nao Informado.";
       $this->erro_campo = "w04_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w04_enviado == null ){ 
       $this->erro_sql = " Campo Enviado nao Informado.";
       $this->erro_campo = "w04_enviado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w04_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "w04_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w04_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "w04_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->w04_codigo = $w04_codigo; 
     if(($this->w04_codigo == null) || ($this->w04_codigo == "") ){ 
       $this->erro_sql = " Campo w04_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_dae(
                                       w04_codigo 
                                      ,w04_inscr 
                                      ,w04_enviado 
                                      ,w04_ano 
                                      ,w04_data 
                       )
                values (
                                $this->w04_codigo 
                               ,$this->w04_inscr 
                               ,'$this->w04_enviado' 
                               ,'$this->w04_ano' 
                               ,".($this->w04_data == "null" || $this->w04_data == ""?"null":"'".$this->w04_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabela de geração do dae ($this->w04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabela de geração do dae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabela de geração do dae ($this->w04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w04_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w04_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3615,'$this->w04_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,607,3615,'','".AddSlashes(pg_result($resaco,0,'w04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,607,3616,'','".AddSlashes(pg_result($resaco,0,'w04_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,607,3617,'','".AddSlashes(pg_result($resaco,0,'w04_enviado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,607,4657,'','".AddSlashes(pg_result($resaco,0,'w04_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,607,4765,'','".AddSlashes(pg_result($resaco,0,'w04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w04_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_dae set ";
     $virgula = "";
     if(trim($this->w04_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w04_codigo"])){ 
        if(trim($this->w04_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w04_codigo"])){ 
           $this->w04_codigo = "0" ; 
        } 
       $sql  .= $virgula." w04_codigo = $this->w04_codigo ";
       $virgula = ",";
       if(trim($this->w04_codigo) == null ){ 
         $this->erro_sql = " Campo Código do dae nao Informado.";
         $this->erro_campo = "w04_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w04_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w04_inscr"])){ 
        if(trim($this->w04_inscr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["w04_inscr"])){ 
           $this->w04_inscr = "0" ; 
        } 
       $sql  .= $virgula." w04_inscr = $this->w04_inscr ";
       $virgula = ",";
       if(trim($this->w04_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição consultada nao Informado.";
         $this->erro_campo = "w04_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w04_enviado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w04_enviado"])){ 
       $sql  .= $virgula." w04_enviado = '$this->w04_enviado' ";
       $virgula = ",";
       if(trim($this->w04_enviado) == null ){ 
         $this->erro_sql = " Campo Enviado nao Informado.";
         $this->erro_campo = "w04_enviado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w04_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w04_ano"])){ 
       $sql  .= $virgula." w04_ano = '$this->w04_ano' ";
       $virgula = ",";
       if(trim($this->w04_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "w04_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w04_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w04_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w04_data_dia"] !="") ){ 
       $sql  .= $virgula." w04_data = '$this->w04_data' ";
       $virgula = ",";
       if(trim($this->w04_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "w04_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w04_data_dia"])){ 
         $sql  .= $virgula." w04_data = null ";
         $virgula = ",";
         if(trim($this->w04_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "w04_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($w04_codigo!=null){
       $sql .= " w04_codigo = $this->w04_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w04_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3615,'$this->w04_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w04_codigo"]))
           $resac = db_query("insert into db_acount values($acount,607,3615,'".AddSlashes(pg_result($resaco,$conresaco,'w04_codigo'))."','$this->w04_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w04_inscr"]))
           $resac = db_query("insert into db_acount values($acount,607,3616,'".AddSlashes(pg_result($resaco,$conresaco,'w04_inscr'))."','$this->w04_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w04_enviado"]))
           $resac = db_query("insert into db_acount values($acount,607,3617,'".AddSlashes(pg_result($resaco,$conresaco,'w04_enviado'))."','$this->w04_enviado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w04_ano"]))
           $resac = db_query("insert into db_acount values($acount,607,4657,'".AddSlashes(pg_result($resaco,$conresaco,'w04_ano'))."','$this->w04_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w04_data"]))
           $resac = db_query("insert into db_acount values($acount,607,4765,'".AddSlashes(pg_result($resaco,$conresaco,'w04_data'))."','$this->w04_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de geração do dae nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de geração do dae nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w04_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w04_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3615,'$w04_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,607,3615,'','".AddSlashes(pg_result($resaco,$iresaco,'w04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,607,3616,'','".AddSlashes(pg_result($resaco,$iresaco,'w04_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,607,3617,'','".AddSlashes(pg_result($resaco,$iresaco,'w04_enviado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,607,4657,'','".AddSlashes(pg_result($resaco,$iresaco,'w04_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,607,4765,'','".AddSlashes(pg_result($resaco,$iresaco,'w04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_dae
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w04_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w04_codigo = $w04_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabela de geração do dae nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabela de geração do dae nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w04_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_dae";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $w04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_dae ";
     $sql2 = "";
     if($dbwhere==""){
       if($w04_codigo!=null ){
         $sql2 .= " where db_dae.w04_codigo = $w04_codigo "; 
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
   function sql_query_file ( $w04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_dae ";
     $sql2 = "";
     if($dbwhere==""){
       if($w04_codigo!=null ){
         $sql2 .= " where db_dae.w04_codigo = $w04_codigo "; 
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