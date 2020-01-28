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

//MODULO: educação
//CLASSE DA ENTIDADE notas
class cl_notas { 
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
   var $ed11_i_codigo = 0; 
   var $ed11_d_data_dia = null; 
   var $ed11_d_data_mes = null; 
   var $ed11_d_data_ano = null; 
   var $ed11_d_data = null; 
   var $ed11_i_matriculas = 0; 
   var $ed11_f_media = 0; 
   var $ed11_c_fechado = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed11_i_codigo = int8 = Código 
                 ed11_d_data = date = Data 
                 ed11_i_matriculas = int8 = Código da Matrícula 
                 ed11_f_media = float4 = Média 
                 ed11_c_fechado = char(1) = Fechado 
                 ";
   //funcao construtor da classe 
   function cl_notas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notas"); 
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
       $this->ed11_i_codigo = ($this->ed11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]:$this->ed11_i_codigo);
       if($this->ed11_d_data == ""){
         $this->ed11_d_data_dia = ($this->ed11_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_d_data_dia"]:$this->ed11_d_data_dia);
         $this->ed11_d_data_mes = ($this->ed11_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_d_data_mes"]:$this->ed11_d_data_mes);
         $this->ed11_d_data_ano = ($this->ed11_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_d_data_ano"]:$this->ed11_d_data_ano);
         if($this->ed11_d_data_dia != ""){
            $this->ed11_d_data = $this->ed11_d_data_ano."-".$this->ed11_d_data_mes."-".$this->ed11_d_data_dia;
         }
       }
       $this->ed11_i_matriculas = ($this->ed11_i_matriculas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_matriculas"]:$this->ed11_i_matriculas);
       $this->ed11_f_media = ($this->ed11_f_media == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_f_media"]:$this->ed11_f_media);
       $this->ed11_c_fechado = ($this->ed11_c_fechado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_c_fechado"]:$this->ed11_c_fechado);
     }else{
       $this->ed11_i_codigo = ($this->ed11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]:$this->ed11_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed11_i_codigo){ 
      $this->atualizacampos();
     if($this->ed11_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed11_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed11_i_matriculas == null ){ 
       $this->erro_sql = " Campo Código da Matrícula nao Informado.";
       $this->erro_campo = "ed11_i_matriculas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed11_f_media == null ){ 
       $this->erro_sql = " Campo Média nao Informado.";
       $this->erro_campo = "ed11_f_media";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed11_c_fechado == null ){ 
       $this->erro_sql = " Campo Fechado nao Informado.";
       $this->erro_campo = "ed11_c_fechado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed11_i_codigo == "" || $ed11_i_codigo == null ){
       $result = @pg_query("select nextval('notas_ed11_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notas_ed11_i_codigo_seq do campo: ed11_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed11_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from notas_ed11_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed11_i_codigo)){
         $this->erro_sql = " Campo ed11_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed11_i_codigo = $ed11_i_codigo; 
       }
     }
     if(($this->ed11_i_codigo == null) || ($this->ed11_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed11_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notas(
                                       ed11_i_codigo 
                                      ,ed11_d_data 
                                      ,ed11_i_matriculas 
                                      ,ed11_f_media 
                                      ,ed11_c_fechado 
                       )
                values (
                                $this->ed11_i_codigo 
                               ,".($this->ed11_d_data == "null" || $this->ed11_d_data == ""?"null":"'".$this->ed11_d_data."'")." 
                               ,$this->ed11_i_matriculas 
                               ,$this->ed11_f_media 
                               ,'$this->ed11_c_fechado' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notas ($this->ed11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notas ($this->ed11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed11_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006022,'$this->ed11_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006004,1006022,'','".AddSlashes(pg_result($resaco,0,'ed11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006004,1006206,'','".AddSlashes(pg_result($resaco,0,'ed11_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006004,1006060,'','".AddSlashes(pg_result($resaco,0,'ed11_i_matriculas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006004,1006026,'','".AddSlashes(pg_result($resaco,0,'ed11_f_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006004,1006316,'','".AddSlashes(pg_result($resaco,0,'ed11_c_fechado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed11_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update notas set ";
     $virgula = "";
     if(trim($this->ed11_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"])){ 
       $sql  .= $virgula." ed11_i_codigo = $this->ed11_i_codigo ";
       $virgula = ",";
       if(trim($this->ed11_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed11_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed11_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed11_d_data = '$this->ed11_d_data' ";
       $virgula = ",";
       if(trim($this->ed11_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed11_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_d_data_dia"])){ 
         $sql  .= $virgula." ed11_d_data = null ";
         $virgula = ",";
         if(trim($this->ed11_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed11_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed11_i_matriculas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_matriculas"])){ 
       $sql  .= $virgula." ed11_i_matriculas = $this->ed11_i_matriculas ";
       $virgula = ",";
       if(trim($this->ed11_i_matriculas) == null ){ 
         $this->erro_sql = " Campo Código da Matrícula nao Informado.";
         $this->erro_campo = "ed11_i_matriculas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_f_media)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_f_media"])){ 
       $sql  .= $virgula." ed11_f_media = $this->ed11_f_media ";
       $virgula = ",";
       if(trim($this->ed11_f_media) == null ){ 
         $this->erro_sql = " Campo Média nao Informado.";
         $this->erro_campo = "ed11_f_media";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed11_c_fechado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_fechado"])){ 
       $sql  .= $virgula." ed11_c_fechado = '$this->ed11_c_fechado' ";
       $virgula = ",";
       if(trim($this->ed11_c_fechado) == null ){ 
         $this->erro_sql = " Campo Fechado nao Informado.";
         $this->erro_campo = "ed11_c_fechado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed11_i_codigo!=null){
       $sql .= " ed11_i_codigo = $ed11_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed11_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006022,'$this->ed11_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006004,1006022,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_codigo'))."','$this->ed11_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1006004,1006206,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_d_data'))."','$this->ed11_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_i_matriculas"]))
           $resac = pg_query("insert into db_acount values($acount,1006004,1006060,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_i_matriculas'))."','$this->ed11_i_matriculas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_f_media"]))
           $resac = pg_query("insert into db_acount values($acount,1006004,1006026,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_f_media'))."','$this->ed11_f_media',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed11_c_fechado"]))
           $resac = pg_query("insert into db_acount values($acount,1006004,1006316,'".AddSlashes(pg_result($resaco,$conresaco,'ed11_c_fechado'))."','$this->ed11_c_fechado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed11_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed11_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006022,'$ed11_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006004,1006022,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006004,1006206,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006004,1006060,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_i_matriculas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006004,1006026,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_f_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006004,1006316,'','".AddSlashes(pg_result($resaco,$iresaco,'ed11_c_fechado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed11_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed11_i_codigo = $ed11_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed11_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:notas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notas ";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = notas.ed11_i_matriculas";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = matriculas.ed09_i_escola";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed11_i_codigo!=null ){
         $sql2 .= " where notas.ed11_i_codigo = $ed11_i_codigo "; 
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
   function sql_query_file ( $ed11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed11_i_codigo!=null ){
         $sql2 .= " where notas.ed11_i_codigo = $ed11_i_codigo "; 
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