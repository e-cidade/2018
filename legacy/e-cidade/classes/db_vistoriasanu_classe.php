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

//MODULO: fiscal
//CLASSE DA ENTIDADE vistoriasanu
class cl_vistoriasanu { 
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
   var $y28_codigo = 0; 
   var $y28_codvist = 0; 
   var $y28_data_dia = null; 
   var $y28_data_mes = null; 
   var $y28_data_ano = null; 
   var $y28_data = null; 
   var $y28_hora = null; 
   var $y28_usuario = 0; 
   var $y28_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y28_codigo = int4 = Cod. Seq. Anulação 
                 y28_codvist = int4 = Código da Vistoria 
                 y28_data = date = Data 
                 y28_hora = char(5) = Hora 
                 y28_usuario = int4 = Cod. Usuário 
                 y28_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_vistoriasanu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vistoriasanu"); 
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
       $this->y28_codigo = ($this->y28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_codigo"]:$this->y28_codigo);
       $this->y28_codvist = ($this->y28_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_codvist"]:$this->y28_codvist);
       if($this->y28_data == ""){
         $this->y28_data_dia = ($this->y28_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_data_dia"]:$this->y28_data_dia);
         $this->y28_data_mes = ($this->y28_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_data_mes"]:$this->y28_data_mes);
         $this->y28_data_ano = ($this->y28_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_data_ano"]:$this->y28_data_ano);
         if($this->y28_data_dia != ""){
            $this->y28_data = $this->y28_data_ano."-".$this->y28_data_mes."-".$this->y28_data_dia;
         }
       }
       $this->y28_hora = ($this->y28_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_hora"]:$this->y28_hora);
       $this->y28_usuario = ($this->y28_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_usuario"]:$this->y28_usuario);
       $this->y28_motivo = ($this->y28_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_motivo"]:$this->y28_motivo);
     }else{
       $this->y28_codigo = ($this->y28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y28_codigo"]:$this->y28_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($y28_codigo){ 
      $this->atualizacampos();
     if($this->y28_codvist == null ){ 
       $this->erro_sql = " Campo Código da Vistoria nao Informado.";
       $this->erro_campo = "y28_codvist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y28_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "y28_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y28_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "y28_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y28_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "y28_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y28_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "y28_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y28_codigo == "" || $y28_codigo == null ){
       $result = db_query("select nextval('vistoriasanu_y28_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vistoriasanu_y28_codigo_seq do campo: y28_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y28_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vistoriasanu_y28_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y28_codigo)){
         $this->erro_sql = " Campo y28_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y28_codigo = $y28_codigo; 
       }
     }
     if(($this->y28_codigo == null) || ($this->y28_codigo == "") ){ 
       $this->erro_sql = " Campo y28_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vistoriasanu(
                                       y28_codigo 
                                      ,y28_codvist 
                                      ,y28_data 
                                      ,y28_hora 
                                      ,y28_usuario 
                                      ,y28_motivo 
                       )
                values (
                                $this->y28_codigo 
                               ,$this->y28_codvist 
                               ,".($this->y28_data == "null" || $this->y28_data == ""?"null":"'".$this->y28_data."'")." 
                               ,'$this->y28_hora' 
                               ,$this->y28_usuario 
                               ,'$this->y28_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vistorias Anuladas ($this->y28_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vistorias Anuladas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vistorias Anuladas ($this->y28_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y28_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y28_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9501,'$this->y28_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1632,9501,'','".AddSlashes(pg_result($resaco,0,'y28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1632,9502,'','".AddSlashes(pg_result($resaco,0,'y28_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1632,9503,'','".AddSlashes(pg_result($resaco,0,'y28_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1632,9504,'','".AddSlashes(pg_result($resaco,0,'y28_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1632,9505,'','".AddSlashes(pg_result($resaco,0,'y28_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1632,9506,'','".AddSlashes(pg_result($resaco,0,'y28_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y28_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vistoriasanu set ";
     $virgula = "";
     if(trim($this->y28_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_codigo"])){ 
       $sql  .= $virgula." y28_codigo = $this->y28_codigo ";
       $virgula = ",";
       if(trim($this->y28_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Seq. Anulação nao Informado.";
         $this->erro_campo = "y28_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y28_codvist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_codvist"])){ 
       $sql  .= $virgula." y28_codvist = $this->y28_codvist ";
       $virgula = ",";
       if(trim($this->y28_codvist) == null ){ 
         $this->erro_sql = " Campo Código da Vistoria nao Informado.";
         $this->erro_campo = "y28_codvist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y28_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y28_data_dia"] !="") ){ 
       $sql  .= $virgula." y28_data = '$this->y28_data' ";
       $virgula = ",";
       if(trim($this->y28_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "y28_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y28_data_dia"])){ 
         $sql  .= $virgula." y28_data = null ";
         $virgula = ",";
         if(trim($this->y28_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "y28_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y28_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_hora"])){ 
       $sql  .= $virgula." y28_hora = '$this->y28_hora' ";
       $virgula = ",";
       if(trim($this->y28_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "y28_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y28_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_usuario"])){ 
       $sql  .= $virgula." y28_usuario = $this->y28_usuario ";
       $virgula = ",";
       if(trim($this->y28_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y28_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y28_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y28_motivo"])){ 
       $sql  .= $virgula." y28_motivo = '$this->y28_motivo' ";
       $virgula = ",";
       if(trim($this->y28_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "y28_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y28_codigo!=null){
       $sql .= " y28_codigo = $this->y28_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y28_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9501,'$this->y28_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1632,9501,'".AddSlashes(pg_result($resaco,$conresaco,'y28_codigo'))."','$this->y28_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_codvist"]))
           $resac = db_query("insert into db_acount values($acount,1632,9502,'".AddSlashes(pg_result($resaco,$conresaco,'y28_codvist'))."','$this->y28_codvist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_data"]))
           $resac = db_query("insert into db_acount values($acount,1632,9503,'".AddSlashes(pg_result($resaco,$conresaco,'y28_data'))."','$this->y28_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_hora"]))
           $resac = db_query("insert into db_acount values($acount,1632,9504,'".AddSlashes(pg_result($resaco,$conresaco,'y28_hora'))."','$this->y28_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1632,9505,'".AddSlashes(pg_result($resaco,$conresaco,'y28_usuario'))."','$this->y28_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y28_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1632,9506,'".AddSlashes(pg_result($resaco,$conresaco,'y28_motivo'))."','$this->y28_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vistorias Anuladas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y28_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vistorias Anuladas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y28_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y28_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y28_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y28_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9501,'$y28_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1632,9501,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1632,9502,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1632,9503,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1632,9504,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1632,9505,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1632,9506,'','".AddSlashes(pg_result($resaco,$iresaco,'y28_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vistoriasanu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y28_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y28_codigo = $y28_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vistorias Anuladas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y28_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vistorias Anuladas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y28_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y28_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vistoriasanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y28_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriasanu ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistoriasanu.y28_usuario";
     $sql .= "      inner join vistorias  on  vistorias.y70_codvist = vistoriasanu.y28_codvist";
     //  $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistorias.y70_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vistorias.y70_coddepto";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = vistorias.y70_ultandam";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistorias.y70_tipovist";
     $sql2 = "";
     if($dbwhere==""){
       if($y28_codigo!=null ){
         $sql2 .= " where vistoriasanu.y28_codigo = $y28_codigo "; 
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
   function sql_query_file ( $y28_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistoriasanu ";
     $sql2 = "";
     if($dbwhere==""){
       if($y28_codigo!=null ){
         $sql2 .= " where vistoriasanu.y28_codigo = $y28_codigo "; 
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