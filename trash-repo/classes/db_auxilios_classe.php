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

//MODULO: educa��o
//CLASSE DA ENTIDADE auxilios
class cl_auxilios { 
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
   var $ed21_i_codigo = 0; 
   var $ed21_i_aluno = 0; 
   var $ed21_c_tipo = null; 
   var $ed21_d_inicio_dia = null; 
   var $ed21_d_inicio_mes = null; 
   var $ed21_d_inicio_ano = null; 
   var $ed21_d_inicio = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed21_i_codigo = int8 = C�digo 
                 ed21_i_aluno = int8 = C�digo do Aluno 
                 ed21_c_tipo = char(50) = Tipo de Aux�lio 
                 ed21_d_inicio = date = In�cio do Aux�lio 
                 ";
   //funcao construtor da classe 
   function cl_auxilios() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("auxilios"); 
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
       $this->ed21_i_codigo = ($this->ed21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_i_codigo"]:$this->ed21_i_codigo);
       $this->ed21_i_aluno = ($this->ed21_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_i_aluno"]:$this->ed21_i_aluno);
       $this->ed21_c_tipo = ($this->ed21_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_c_tipo"]:$this->ed21_c_tipo);
       if($this->ed21_d_inicio == ""){
         $this->ed21_d_inicio_dia = ($this->ed21_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_dia"]:$this->ed21_d_inicio_dia);
         $this->ed21_d_inicio_mes = ($this->ed21_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_mes"]:$this->ed21_d_inicio_mes);
         $this->ed21_d_inicio_ano = ($this->ed21_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_ano"]:$this->ed21_d_inicio_ano);
         if($this->ed21_d_inicio_dia != ""){
            $this->ed21_d_inicio = $this->ed21_d_inicio_ano."-".$this->ed21_d_inicio_mes."-".$this->ed21_d_inicio_dia;
         }
       }
     }else{
       $this->ed21_i_codigo = ($this->ed21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed21_i_codigo"]:$this->ed21_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed21_i_codigo){ 
      $this->atualizacampos();
     if($this->ed21_i_aluno == null ){ 
       $this->erro_sql = " Campo C�digo do Aluno nao Informado.";
       $this->erro_campo = "ed21_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed21_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Aux�lio nao Informado.";
       $this->erro_campo = "ed21_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed21_d_inicio == null ){ 
       $this->erro_sql = " Campo In�cio do Aux�lio nao Informado.";
       $this->erro_campo = "ed21_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed21_i_codigo == "" || $ed21_i_codigo == null ){
       $result = @pg_query("select nextval('auxilios_ed21_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: auxilios_ed21_i_codigo_seq do campo: ed21_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed21_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from auxilios_ed21_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed21_i_codigo)){
         $this->erro_sql = " Campo ed21_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed21_i_codigo = $ed21_i_codigo; 
       }
     }
     if(($this->ed21_i_codigo == null) || ($this->ed21_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed21_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into auxilios(
                                       ed21_i_codigo 
                                      ,ed21_i_aluno 
                                      ,ed21_c_tipo 
                                      ,ed21_d_inicio 
                       )
                values (
                                $this->ed21_i_codigo 
                               ,$this->ed21_i_aluno 
                               ,'$this->ed21_c_tipo' 
                               ,".($this->ed21_d_inicio == "null" || $this->ed21_d_inicio == ""?"null":"'".$this->ed21_d_inicio."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aux�lios ($this->ed21_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aux�lios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aux�lios ($this->ed21_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed21_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed21_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006076,'$this->ed21_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006014,1006076,'','".AddSlashes(pg_result($resaco,0,'ed21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006014,1006077,'','".AddSlashes(pg_result($resaco,0,'ed21_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006014,1006078,'','".AddSlashes(pg_result($resaco,0,'ed21_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006014,1006079,'','".AddSlashes(pg_result($resaco,0,'ed21_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed21_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update auxilios set ";
     $virgula = "";
     if(trim($this->ed21_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed21_i_codigo"])){ 
       $sql  .= $virgula." ed21_i_codigo = $this->ed21_i_codigo ";
       $virgula = ",";
       if(trim($this->ed21_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed21_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed21_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed21_i_aluno"])){ 
       $sql  .= $virgula." ed21_i_aluno = $this->ed21_i_aluno ";
       $virgula = ",";
       if(trim($this->ed21_i_aluno) == null ){ 
         $this->erro_sql = " Campo C�digo do Aluno nao Informado.";
         $this->erro_campo = "ed21_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed21_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed21_c_tipo"])){ 
       $sql  .= $virgula." ed21_c_tipo = '$this->ed21_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed21_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Aux�lio nao Informado.";
         $this->erro_campo = "ed21_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed21_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed21_d_inicio = '$this->ed21_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed21_d_inicio) == null ){ 
         $this->erro_sql = " Campo In�cio do Aux�lio nao Informado.";
         $this->erro_campo = "ed21_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio_dia"])){ 
         $sql  .= $virgula." ed21_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed21_d_inicio) == null ){ 
           $this->erro_sql = " Campo In�cio do Aux�lio nao Informado.";
           $this->erro_campo = "ed21_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed21_i_codigo!=null){
       $sql .= " ed21_i_codigo = $this->ed21_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed21_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006076,'$this->ed21_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed21_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006014,1006076,'".AddSlashes(pg_result($resaco,$conresaco,'ed21_i_codigo'))."','$this->ed21_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed21_i_aluno"]))
           $resac = pg_query("insert into db_acount values($acount,1006014,1006077,'".AddSlashes(pg_result($resaco,$conresaco,'ed21_i_aluno'))."','$this->ed21_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed21_c_tipo"]))
           $resac = pg_query("insert into db_acount values($acount,1006014,1006078,'".AddSlashes(pg_result($resaco,$conresaco,'ed21_c_tipo'))."','$this->ed21_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed21_d_inicio"]))
           $resac = pg_query("insert into db_acount values($acount,1006014,1006079,'".AddSlashes(pg_result($resaco,$conresaco,'ed21_d_inicio'))."','$this->ed21_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aux�lios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed21_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aux�lios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed21_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed21_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006076,'$ed21_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006014,1006076,'','".AddSlashes(pg_result($resaco,$iresaco,'ed21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006014,1006077,'','".AddSlashes(pg_result($resaco,$iresaco,'ed21_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006014,1006078,'','".AddSlashes(pg_result($resaco,$iresaco,'ed21_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006014,1006079,'','".AddSlashes(pg_result($resaco,$iresaco,'ed21_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from auxilios
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed21_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed21_i_codigo = $ed21_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aux�lios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed21_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aux�lios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed21_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:auxilios";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from auxilios ";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = auxilios.ed21_i_aluno";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = alunos.ed07_i_codigo and  cgm.z01_numcgm = alunos.ed07_i_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($ed21_i_codigo!=null ){
         $sql2 .= " where auxilios.ed21_i_codigo = $ed21_i_codigo "; 
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
   function sql_query_file ( $ed21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from auxilios ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed21_i_codigo!=null ){
         $sql2 .= " where auxilios.ed21_i_codigo = $ed21_i_codigo "; 
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