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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_infaluno
class cl_mer_infaluno { 
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
   var $me14_i_codigo = 0; 
   var $me14_f_peso = 0; 
   var $me14_f_altura = 0; 
   var $me14_i_aluno = 0; 
   var $me14_i_ano = ""; 
   var $me14_i_mes = ""; 
   var $me14_i_periodocalendario = ""; 
   var $me14_d_data_dia = null; 
   var $me14_d_data_mes = null; 
   var $me14_d_data_ano = null; 
   var $me14_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me14_i_codigo = int4 = Código 
                 me14_f_peso = float4 = Peso 
                 me14_f_altura = float4 = Altura 
                 me14_i_aluno = int4 = Aluno 
                 me14_i_ano = int4 = Ano 
                 me14_i_mes = int4 = Mês 
                 me14_i_periodocalendario = int4 = Período de Avaliação 
                 me14_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_mer_infaluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_infaluno"); 
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
       $this->me14_i_codigo = ($this->me14_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_codigo"]:$this->me14_i_codigo);
       $this->me14_f_peso = ($this->me14_f_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_f_peso"]:$this->me14_f_peso);
       $this->me14_f_altura = ($this->me14_f_altura == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_f_altura"]:$this->me14_f_altura);
       $this->me14_i_aluno = ($this->me14_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_aluno"]:$this->me14_i_aluno);
       $this->me14_i_ano = ($this->me14_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_ano"]:$this->me14_i_ano);
       $this->me14_i_mes = ($this->me14_i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_mes"]:$this->me14_i_mes);
       $this->me14_i_periodocalendario = ($this->me14_i_periodocalendario == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_periodocalendario"]:$this->me14_i_periodocalendario);
       if($this->me14_d_data == ""){
         $this->me14_d_data_dia = ($this->me14_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_d_data_dia"]:$this->me14_d_data_dia);
         $this->me14_d_data_mes = ($this->me14_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_d_data_mes"]:$this->me14_d_data_mes);
         $this->me14_d_data_ano = ($this->me14_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_d_data_ano"]:$this->me14_d_data_ano);
         if($this->me14_d_data_dia != ""){
            $this->me14_d_data = $this->me14_d_data_ano."-".$this->me14_d_data_mes."-".$this->me14_d_data_dia;
         }
       }
     }else{
       $this->me14_i_codigo = ($this->me14_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me14_i_codigo"]:$this->me14_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me14_i_codigo){ 
      $this->atualizacampos();
     if($this->me14_f_peso == null ){ 
       $this->erro_sql = " Campo Peso nao Informado.";
       $this->erro_campo = "me14_f_peso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me14_f_altura == null ){ 
       $this->erro_sql = " Campo Altura nao Informado.";
       $this->erro_campo = "me14_f_altura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me14_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "me14_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me14_i_ano == null ){ 
       $this->me14_i_ano = "null";
     }
     if($this->me14_i_mes == null ){ 
       $this->me14_i_mes = "null";
     }
     if($this->me14_i_periodocalendario == null ){ 
       $this->me14_i_periodocalendario = "null";
     }
     if($this->me14_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "me14_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me14_i_codigo == "" || $me14_i_codigo == null ){
       $result = db_query("select nextval('merinfaluno_me14_codigo')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: merinfaluno_me14_codigo do campo: me14_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me14_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from merinfaluno_me14_codigo");
       if(($result != false) && (pg_result($result,0,0) < $me14_i_codigo)){
         $this->erro_sql = " Campo me14_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me14_i_codigo = $me14_i_codigo; 
       }
     }
     if(($this->me14_i_codigo == null) || ($this->me14_i_codigo == "") ){ 
       $this->erro_sql = " Campo me14_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_infaluno(
                                       me14_i_codigo 
                                      ,me14_f_peso 
                                      ,me14_f_altura 
                                      ,me14_i_aluno 
                                      ,me14_i_ano 
                                      ,me14_i_mes 
                                      ,me14_i_periodocalendario 
                                      ,me14_d_data 
                       )
                values (
                                $this->me14_i_codigo 
                               ,$this->me14_f_peso 
                               ,$this->me14_f_altura 
                               ,$this->me14_i_aluno 
                               ,$this->me14_i_ano 
                               ,$this->me14_i_mes 
                               ,$this->me14_i_periodocalendario 
                               ,".($this->me14_d_data == "null" || $this->me14_d_data == ""?"null":"'".$this->me14_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_infaluno ($this->me14_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_infaluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_infaluno ($this->me14_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me14_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me14_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12758,'$this->me14_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2234,12758,'','".AddSlashes(pg_result($resaco,0,'me14_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,12759,'','".AddSlashes(pg_result($resaco,0,'me14_f_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,12760,'','".AddSlashes(pg_result($resaco,0,'me14_f_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,12762,'','".AddSlashes(pg_result($resaco,0,'me14_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,17304,'','".AddSlashes(pg_result($resaco,0,'me14_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,17303,'','".AddSlashes(pg_result($resaco,0,'me14_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,17305,'','".AddSlashes(pg_result($resaco,0,'me14_i_periodocalendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2234,12761,'','".AddSlashes(pg_result($resaco,0,'me14_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me14_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_infaluno set ";
     $virgula = "";
     if(trim($this->me14_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me14_i_codigo"])){ 
       $sql  .= $virgula." me14_i_codigo = $this->me14_i_codigo ";
       $virgula = ",";
       if(trim($this->me14_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me14_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me14_f_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me14_f_peso"])){ 
       $sql  .= $virgula." me14_f_peso = $this->me14_f_peso ";
       $virgula = ",";
       if(trim($this->me14_f_peso) == null ){ 
         $this->erro_sql = " Campo Peso nao Informado.";
         $this->erro_campo = "me14_f_peso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me14_f_altura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me14_f_altura"])){ 
       $sql  .= $virgula." me14_f_altura = $this->me14_f_altura ";
       $virgula = ",";
       if(trim($this->me14_f_altura) == null ){ 
         $this->erro_sql = " Campo Altura nao Informado.";
         $this->erro_campo = "me14_f_altura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me14_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me14_i_aluno"])){ 
       $sql  .= $virgula." me14_i_aluno = $this->me14_i_aluno ";
       $virgula = ",";
       if(trim($this->me14_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "me14_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

        if(trim($this->me14_i_ano)== null){ 
           $this->me14_i_ano = "null" ; 
        } 
       $sql  .= $virgula." me14_i_ano = $this->me14_i_ano ";
       $virgula = ",";
 
        if(trim($this->me14_i_mes)== null){ 
           $this->me14_i_mes = "null" ; 
        } 
       $sql  .= $virgula." me14_i_mes = $this->me14_i_mes ";
       $virgula = ",";

 
        if(trim($this->me14_i_periodocalendario)== null){ 
           $this->me14_i_periodocalendario = "null" ; 
        } 
       $sql  .= $virgula." me14_i_periodocalendario = $this->me14_i_periodocalendario ";
       $virgula = ",";

     if(trim($this->me14_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me14_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me14_d_data_dia"] !="") ){ 
       $sql  .= $virgula." me14_d_data = '$this->me14_d_data' ";
       $virgula = ",";
       if(trim($this->me14_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "me14_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me14_d_data_dia"])){ 
         $sql  .= $virgula." me14_d_data = null ";
         $virgula = ",";
         if(trim($this->me14_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "me14_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($me14_i_codigo!=null){
       $sql .= " me14_i_codigo = $this->me14_i_codigo";
     }

     $resaco = $this->sql_record($this->sql_query_file($this->me14_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12758,'$this->me14_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_i_codigo"]) || $this->me14_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2234,12758,'".AddSlashes(pg_result($resaco,$conresaco,'me14_i_codigo'))."','$this->me14_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_f_peso"]) || $this->me14_f_peso != "")
           $resac = db_query("insert into db_acount values($acount,2234,12759,'".AddSlashes(pg_result($resaco,$conresaco,'me14_f_peso'))."','$this->me14_f_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_f_altura"]) || $this->me14_f_altura != "")
           $resac = db_query("insert into db_acount values($acount,2234,12760,'".AddSlashes(pg_result($resaco,$conresaco,'me14_f_altura'))."','$this->me14_f_altura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_i_aluno"]) || $this->me14_i_aluno != "")
           $resac = db_query("insert into db_acount values($acount,2234,12762,'".AddSlashes(pg_result($resaco,$conresaco,'me14_i_aluno'))."','$this->me14_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_i_ano"]) || $this->me14_i_ano != "")
           $resac = db_query("insert into db_acount values($acount,2234,17304,'".AddSlashes(pg_result($resaco,$conresaco,'me14_i_ano'))."','$this->me14_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_i_mes"]) || $this->me14_i_mes != "")
           $resac = db_query("insert into db_acount values($acount,2234,17303,'".AddSlashes(pg_result($resaco,$conresaco,'me14_i_mes'))."','$this->me14_i_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_i_periodocalendario"]) || $this->me14_i_periodocalendario != "")
           $resac = db_query("insert into db_acount values($acount,2234,17305,'".AddSlashes(pg_result($resaco,$conresaco,'me14_i_periodocalendario'))."','$this->me14_i_periodocalendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me14_d_data"]) || $this->me14_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2234,12761,'".AddSlashes(pg_result($resaco,$conresaco,'me14_d_data'))."','$this->me14_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_infaluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me14_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_infaluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me14_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me14_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12758,'$me14_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2234,12758,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,12759,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_f_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,12760,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_f_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,12762,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,17304,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,17303,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_i_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,17305,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_i_periodocalendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2234,12761,'','".AddSlashes(pg_result($resaco,$iresaco,'me14_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_infaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me14_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me14_i_codigo = $me14_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_infaluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me14_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_infaluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me14_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me14_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_infaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me14_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_infaluno ";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = mer_infaluno.me14_i_aluno";
     $sql .= "      left  join periodocalendario  on  periodocalendario.ed53_i_codigo = mer_infaluno.me14_i_periodocalendario";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = periodocalendario.ed53_i_periodoavaliacao";
     $sql .= "      left join calendario  on  calendario.ed52_i_codigo = periodocalendario.ed53_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($me14_i_codigo!=null ){
         $sql2 .= " where mer_infaluno.me14_i_codigo = $me14_i_codigo "; 
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
   function sql_query_file ( $me14_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_infaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($me14_i_codigo!=null ){
         $sql2 .= " where mer_infaluno.me14_i_codigo = $me14_i_codigo "; 
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