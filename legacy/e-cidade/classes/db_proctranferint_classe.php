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

//MODULO: protocolo
//CLASSE DA ENTIDADE proctranferint
class cl_proctranferint { 
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
   var $p88_codigo = 0; 
   var $p88_data_dia = null; 
   var $p88_data_mes = null; 
   var $p88_data_ano = null; 
   var $p88_data = null; 
   var $p88_hora = null; 
   var $p88_usuario = 0; 
   var $p88_despacho = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p88_codigo = int8 = codigo 
                 p88_data = date = Data  da Transfêrencia 
                 p88_hora = varchar(5) = Hora da Transfêrencia 
                 p88_usuario = int4 = Usuário atual 
                 p88_despacho = text = Despacho Interno 
                 ";
   //funcao construtor da classe 
   function cl_proctranferint() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("proctranferint"); 
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
       $this->p88_codigo = ($this->p88_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_codigo"]:$this->p88_codigo);
       if($this->p88_data == ""){
         $this->p88_data_dia = ($this->p88_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_data_dia"]:$this->p88_data_dia);
         $this->p88_data_mes = ($this->p88_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_data_mes"]:$this->p88_data_mes);
         $this->p88_data_ano = ($this->p88_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_data_ano"]:$this->p88_data_ano);
         if($this->p88_data_dia != ""){
            $this->p88_data = $this->p88_data_ano."-".$this->p88_data_mes."-".$this->p88_data_dia;
         }
       }
       $this->p88_hora = ($this->p88_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_hora"]:$this->p88_hora);
       $this->p88_usuario = ($this->p88_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_usuario"]:$this->p88_usuario);
       $this->p88_despacho = ($this->p88_despacho == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_despacho"]:$this->p88_despacho);
     }else{
       $this->p88_codigo = ($this->p88_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p88_codigo"]:$this->p88_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($p88_codigo){ 
      $this->atualizacampos();
     if($this->p88_data == null ){ 
       $this->erro_sql = " Campo Data  da Transfêrencia nao Informado.";
       $this->erro_campo = "p88_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p88_hora == null ){ 
       $this->erro_sql = " Campo Hora da Transfêrencia nao Informado.";
       $this->erro_campo = "p88_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p88_usuario == null ){ 
       $this->erro_sql = " Campo Usuário atual nao Informado.";
       $this->erro_campo = "p88_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p88_despacho == null ){ 
       $this->erro_sql = " Campo Despacho Interno nao Informado.";
       $this->erro_campo = "p88_despacho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p88_codigo == "" || $p88_codigo == null ){
       $result = @pg_query("select nextval('proctranferint_p88_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: proctranferint_p88_codigo_seq do campo: p88_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p88_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from proctranferint_p88_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p88_codigo)){
         $this->erro_sql = " Campo p88_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p88_codigo = $p88_codigo; 
       }
     }
     if(($this->p88_codigo == null) || ($this->p88_codigo == "") ){ 
       $this->erro_sql = " Campo p88_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into proctranferint(
                                       p88_codigo 
                                      ,p88_data 
                                      ,p88_hora 
                                      ,p88_usuario 
                                      ,p88_despacho 
                       )
                values (
                                $this->p88_codigo 
                               ,".($this->p88_data == "null" || $this->p88_data == ""?"null":"'".$this->p88_data."'")." 
                               ,'$this->p88_hora' 
                               ,$this->p88_usuario 
                               ,'$this->p88_despacho' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tranferencia interna ($this->p88_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tranferencia interna já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tranferencia interna ($this->p88_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p88_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p88_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,6498,'$this->p88_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1069,6498,'','".AddSlashes(pg_result($resaco,0,'p88_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1069,6500,'','".AddSlashes(pg_result($resaco,0,'p88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1069,6499,'','".AddSlashes(pg_result($resaco,0,'p88_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1069,6501,'','".AddSlashes(pg_result($resaco,0,'p88_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1069,6502,'','".AddSlashes(pg_result($resaco,0,'p88_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p88_codigo=null) { 
      $this->atualizacampos();
     $sql = " update proctranferint set ";
     $virgula = "";
     if(trim($this->p88_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p88_codigo"])){ 
       $sql  .= $virgula." p88_codigo = $this->p88_codigo ";
       $virgula = ",";
       if(trim($this->p88_codigo) == null ){ 
         $this->erro_sql = " Campo codigo nao Informado.";
         $this->erro_campo = "p88_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p88_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p88_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p88_data_dia"] !="") ){ 
       $sql  .= $virgula." p88_data = '$this->p88_data' ";
       $virgula = ",";
       if(trim($this->p88_data) == null ){ 
         $this->erro_sql = " Campo Data  da Transfêrencia nao Informado.";
         $this->erro_campo = "p88_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p88_data_dia"])){ 
         $sql  .= $virgula." p88_data = null ";
         $virgula = ",";
         if(trim($this->p88_data) == null ){ 
           $this->erro_sql = " Campo Data  da Transfêrencia nao Informado.";
           $this->erro_campo = "p88_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p88_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p88_hora"])){ 
       $sql  .= $virgula." p88_hora = '$this->p88_hora' ";
       $virgula = ",";
       if(trim($this->p88_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Transfêrencia nao Informado.";
         $this->erro_campo = "p88_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p88_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p88_usuario"])){ 
       $sql  .= $virgula." p88_usuario = $this->p88_usuario ";
       $virgula = ",";
       if(trim($this->p88_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário atual nao Informado.";
         $this->erro_campo = "p88_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p88_despacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p88_despacho"])){ 
       $sql  .= $virgula." p88_despacho = '$this->p88_despacho' ";
       $virgula = ",";
       if(trim($this->p88_despacho) == null ){ 
         $this->erro_sql = " Campo Despacho Interno nao Informado.";
         $this->erro_campo = "p88_despacho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p88_codigo!=null){
       $sql .= " p88_codigo = $this->p88_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p88_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6498,'$this->p88_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p88_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1069,6498,'".AddSlashes(pg_result($resaco,$conresaco,'p88_codigo'))."','$this->p88_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p88_data"]))
           $resac = pg_query("insert into db_acount values($acount,1069,6500,'".AddSlashes(pg_result($resaco,$conresaco,'p88_data'))."','$this->p88_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p88_hora"]))
           $resac = pg_query("insert into db_acount values($acount,1069,6499,'".AddSlashes(pg_result($resaco,$conresaco,'p88_hora'))."','$this->p88_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p88_usuario"]))
           $resac = pg_query("insert into db_acount values($acount,1069,6501,'".AddSlashes(pg_result($resaco,$conresaco,'p88_usuario'))."','$this->p88_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p88_despacho"]))
           $resac = pg_query("insert into db_acount values($acount,1069,6502,'".AddSlashes(pg_result($resaco,$conresaco,'p88_despacho'))."','$this->p88_despacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tranferencia interna nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p88_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tranferencia interna nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p88_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p88_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p88_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p88_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6498,'".pg_result($resaco,$iresaco,'p88_codigo')."','E')");
         $resac = pg_query("insert into db_acount values($acount,1069,6498,'','".AddSlashes(pg_result($resaco,$iresaco,'p88_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1069,6500,'','".AddSlashes(pg_result($resaco,$iresaco,'p88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1069,6499,'','".AddSlashes(pg_result($resaco,$iresaco,'p88_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1069,6501,'','".AddSlashes(pg_result($resaco,$iresaco,'p88_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1069,6502,'','".AddSlashes(pg_result($resaco,$iresaco,'p88_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from proctranferint
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p88_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p88_codigo = $p88_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tranferencia interna nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p88_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tranferencia interna nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p88_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p88_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:proctranferint";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p88_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctranferint ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = proctranferint.p88_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($p88_codigo!=null ){
         $sql2 .= " where proctranferint.p88_codigo = $p88_codigo "; 
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
   function sql_query_file ( $p88_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctranferint ";
     $sql2 = "";
     if($dbwhere==""){
       if($p88_codigo!=null ){
         $sql2 .= " where proctranferint.p88_codigo = $p88_codigo "; 
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
   function sql_query_usuand ( $p88_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctranferint ";
     $sql .= "      inner join proctransferintusu on p89_codtransferint = p88_codigo";
     $sql .= "      inner join proctransferintand on p87_codtransferint = p88_codigo";
     $sql .= "      inner join db_usuarios as atual  on  atual_usuario = proctranferint.p88_usuario";
     $sql .= "      inner join db_usuarios as destino  on  destino.id_usuario = proctranferintusu.p89_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($p88_codigo!=null ){
         $sql2 .= " where proctranferint.p88_codigo = $p88_codigo "; 
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