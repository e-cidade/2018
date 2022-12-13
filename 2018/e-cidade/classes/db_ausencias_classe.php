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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE ausencias
class cl_ausencias { 
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
   var $sd06_i_codigo = 0; 
   var $sd06_i_especmed = 0; 
   var $sd06_d_data_dia = null; 
   var $sd06_d_data_mes = null; 
   var $sd06_d_data_ano = null; 
   var $sd06_d_data = null; 
   var $sd06_d_inicio_dia = null; 
   var $sd06_d_inicio_mes = null; 
   var $sd06_d_inicio_ano = null; 
   var $sd06_d_inicio = null; 
   var $sd06_d_fim_dia = null; 
   var $sd06_d_fim_mes = null; 
   var $sd06_d_fim_ano = null; 
   var $sd06_d_fim = null; 
   var $sd06_i_tipo = 0; 
   var $sd06_c_horainicio = null; 
   var $sd06_c_horafim = null; 
   var $sd06_i_undmedhorario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd06_i_codigo = int4 = C�digo 
                 sd06_i_especmed = int4 = V�nculo 
                 sd06_d_data = date = Data 
                 sd06_d_inicio = date = In�cio 
                 sd06_d_fim = date = Fim 
                 sd06_i_tipo = int4 = Tipo 
                 sd06_c_horainicio = char(5) = Inicio 
                 sd06_c_horafim = char(5) = Fim 
                 sd06_i_undmedhorario = int4 = C�digo da Grade 
                 ";
   //funcao construtor da classe 
   function cl_ausencias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ausencias"); 
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
       $this->sd06_i_codigo = ($this->sd06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_i_codigo"]:$this->sd06_i_codigo);
       $this->sd06_i_especmed = ($this->sd06_i_especmed == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_i_especmed"]:$this->sd06_i_especmed);
       if($this->sd06_d_data == ""){
         $this->sd06_d_data_dia = ($this->sd06_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_data_dia"]:$this->sd06_d_data_dia);
         $this->sd06_d_data_mes = ($this->sd06_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_data_mes"]:$this->sd06_d_data_mes);
         $this->sd06_d_data_ano = ($this->sd06_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_data_ano"]:$this->sd06_d_data_ano);
         if($this->sd06_d_data_dia != ""){
            $this->sd06_d_data = $this->sd06_d_data_ano."-".$this->sd06_d_data_mes."-".$this->sd06_d_data_dia;
         }
       }
       if($this->sd06_d_inicio == ""){
         $this->sd06_d_inicio_dia = ($this->sd06_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_dia"]:$this->sd06_d_inicio_dia);
         $this->sd06_d_inicio_mes = ($this->sd06_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_mes"]:$this->sd06_d_inicio_mes);
         $this->sd06_d_inicio_ano = ($this->sd06_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_ano"]:$this->sd06_d_inicio_ano);
         if($this->sd06_d_inicio_dia != ""){
            $this->sd06_d_inicio = $this->sd06_d_inicio_ano."-".$this->sd06_d_inicio_mes."-".$this->sd06_d_inicio_dia;
         }
       }
       if($this->sd06_d_fim == ""){
         $this->sd06_d_fim_dia = ($this->sd06_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_dia"]:$this->sd06_d_fim_dia);
         $this->sd06_d_fim_mes = ($this->sd06_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_mes"]:$this->sd06_d_fim_mes);
         $this->sd06_d_fim_ano = ($this->sd06_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_ano"]:$this->sd06_d_fim_ano);
         if($this->sd06_d_fim_dia != ""){
            $this->sd06_d_fim = $this->sd06_d_fim_ano."-".$this->sd06_d_fim_mes."-".$this->sd06_d_fim_dia;
         }
       }
       $this->sd06_i_tipo = ($this->sd06_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_i_tipo"]:$this->sd06_i_tipo);
       $this->sd06_c_horainicio = ($this->sd06_c_horainicio == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_c_horainicio"]:$this->sd06_c_horainicio);
       $this->sd06_c_horafim = ($this->sd06_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_c_horafim"]:$this->sd06_c_horafim);
       $this->sd06_i_undmedhorario = ($this->sd06_i_undmedhorario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_i_undmedhorario"]:$this->sd06_i_undmedhorario);
     }else{
       $this->sd06_i_codigo = ($this->sd06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd06_i_codigo"]:$this->sd06_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd06_i_codigo){ 
      $this->atualizacampos();
     if($this->sd06_i_especmed == null ){ 
       $this->erro_sql = " Campo V�nculo nao Informado.";
       $this->erro_campo = "sd06_i_especmed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd06_d_data == null ){ 
       $this->sd06_d_data = "null";
     }
     if($this->sd06_d_inicio == null ){ 
       $this->erro_sql = " Campo In�cio nao Informado.";
       $this->erro_campo = "sd06_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd06_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "sd06_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd06_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "sd06_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd06_i_undmedhorario == null ){ 
       $this->sd06_i_undmedhorario = "null";
     }
     if($sd06_i_codigo == "" || $sd06_i_codigo == null ){
       $result = db_query("select nextval('ausencias_sd06_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ausencias_sd06_i_codigo_seq do campo: sd06_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd06_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ausencias_sd06_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd06_i_codigo)){
         $this->erro_sql = " Campo sd06_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd06_i_codigo = $sd06_i_codigo; 
       }
     }
     if(($this->sd06_i_codigo == null) || ($this->sd06_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd06_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ausencias(
                                       sd06_i_codigo 
                                      ,sd06_i_especmed 
                                      ,sd06_d_data 
                                      ,sd06_d_inicio 
                                      ,sd06_d_fim 
                                      ,sd06_i_tipo 
                                      ,sd06_c_horainicio 
                                      ,sd06_c_horafim 
                                      ,sd06_i_undmedhorario 
                       )
                values (
                                $this->sd06_i_codigo 
                               ,$this->sd06_i_especmed 
                               ,".($this->sd06_d_data == "null" || $this->sd06_d_data == ""?"null":"'".$this->sd06_d_data."'")." 
                               ,".($this->sd06_d_inicio == "null" || $this->sd06_d_inicio == ""?"null":"'".$this->sd06_d_inicio."'")." 
                               ,".($this->sd06_d_fim == "null" || $this->sd06_d_fim == ""?"null":"'".$this->sd06_d_fim."'")." 
                               ,$this->sd06_i_tipo 
                               ,'$this->sd06_c_horainicio' 
                               ,'$this->sd06_c_horafim' 
                               ,$this->sd06_i_undmedhorario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ausencias ($this->sd06_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ausencias j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ausencias ($this->sd06_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd06_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd06_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1000002,'$this->sd06_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100008,1000002,'','".AddSlashes(pg_result($resaco,0,'sd06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,100049,'','".AddSlashes(pg_result($resaco,0,'sd06_i_especmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,100050,'','".AddSlashes(pg_result($resaco,0,'sd06_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,12558,'','".AddSlashes(pg_result($resaco,0,'sd06_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,12559,'','".AddSlashes(pg_result($resaco,0,'sd06_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,12560,'','".AddSlashes(pg_result($resaco,0,'sd06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,15638,'','".AddSlashes(pg_result($resaco,0,'sd06_c_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,15639,'','".AddSlashes(pg_result($resaco,0,'sd06_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100008,16775,'','".AddSlashes(pg_result($resaco,0,'sd06_i_undmedhorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd06_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ausencias set ";
     $virgula = "";
     if(trim($this->sd06_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_codigo"])){ 
       $sql  .= $virgula." sd06_i_codigo = $this->sd06_i_codigo ";
       $virgula = ",";
       if(trim($this->sd06_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "sd06_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd06_i_especmed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_especmed"])){ 
       $sql  .= $virgula." sd06_i_especmed = $this->sd06_i_especmed ";
       $virgula = ",";
       if(trim($this->sd06_i_especmed) == null ){ 
         $this->erro_sql = " Campo V�nculo nao Informado.";
         $this->erro_campo = "sd06_i_especmed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd06_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd06_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd06_d_data = '$this->sd06_d_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_data_dia"])){ 
         $sql  .= $virgula." sd06_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->sd06_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." sd06_d_inicio = '$this->sd06_d_inicio' ";
       $virgula = ",";
       if(trim($this->sd06_d_inicio) == null ){ 
         $this->erro_sql = " Campo In�cio nao Informado.";
         $this->erro_campo = "sd06_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio_dia"])){ 
         $sql  .= $virgula." sd06_d_inicio = null ";
         $virgula = ",";
         if(trim($this->sd06_d_inicio) == null ){ 
           $this->erro_sql = " Campo In�cio nao Informado.";
           $this->erro_campo = "sd06_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd06_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." sd06_d_fim = '$this->sd06_d_fim' ";
       $virgula = ",";
       if(trim($this->sd06_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "sd06_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_fim_dia"])){ 
         $sql  .= $virgula." sd06_d_fim = null ";
         $virgula = ",";
         if(trim($this->sd06_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "sd06_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd06_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_tipo"])){ 
       $sql  .= $virgula." sd06_i_tipo = $this->sd06_i_tipo ";
       $virgula = ",";
       if(trim($this->sd06_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "sd06_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd06_c_horainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_c_horainicio"])){ 
       $sql  .= $virgula." sd06_c_horainicio = '$this->sd06_c_horainicio' ";
       $virgula = ",";
     }
     if(trim($this->sd06_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_c_horafim"])){ 
       $sql  .= $virgula." sd06_c_horafim = '$this->sd06_c_horafim' ";
       $virgula = ",";
     }
     if(trim($this->sd06_i_undmedhorario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_undmedhorario"])){ 
        if(trim($this->sd06_i_undmedhorario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_undmedhorario"])){ 
           $this->sd06_i_undmedhorario = "null" ; 
        } 
       $sql  .= $virgula." sd06_i_undmedhorario = $this->sd06_i_undmedhorario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($sd06_i_codigo!=null){
       $sql .= " sd06_i_codigo = $this->sd06_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd06_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1000002,'$this->sd06_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_codigo"]) || $this->sd06_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,100008,1000002,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_i_codigo'))."','$this->sd06_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_especmed"]) || $this->sd06_i_especmed != "")
           $resac = db_query("insert into db_acount values($acount,100008,100049,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_i_especmed'))."','$this->sd06_i_especmed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_data"]) || $this->sd06_d_data != "")
           $resac = db_query("insert into db_acount values($acount,100008,100050,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_d_data'))."','$this->sd06_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_inicio"]) || $this->sd06_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,100008,12558,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_d_inicio'))."','$this->sd06_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_d_fim"]) || $this->sd06_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,100008,12559,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_d_fim'))."','$this->sd06_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_tipo"]) || $this->sd06_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,100008,12560,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_i_tipo'))."','$this->sd06_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_c_horainicio"]) || $this->sd06_c_horainicio != "")
           $resac = db_query("insert into db_acount values($acount,100008,15638,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_c_horainicio'))."','$this->sd06_c_horainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_c_horafim"]) || $this->sd06_c_horafim != "")
           $resac = db_query("insert into db_acount values($acount,100008,15639,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_c_horafim'))."','$this->sd06_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd06_i_undmedhorario"]) || $this->sd06_i_undmedhorario != "")
           $resac = db_query("insert into db_acount values($acount,100008,16775,'".AddSlashes(pg_result($resaco,$conresaco,'sd06_i_undmedhorario'))."','$this->sd06_i_undmedhorario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ausencias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd06_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ausencias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd06_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd06_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd06_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd06_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1000002,'$sd06_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100008,1000002,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,100049,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_i_especmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,100050,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,12558,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,12559,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,12560,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,15638,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_c_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,15639,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100008,16775,'','".AddSlashes(pg_result($resaco,$iresaco,'sd06_i_undmedhorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ausencias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd06_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd06_i_codigo = $sd06_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ausencias nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd06_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ausencias nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd06_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd06_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:ausencias";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ausencias ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = ausencias.sd06_i_tipo";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = ausencias.sd06_i_especmed";
     $sql .= "      left  join undmedhorario  on  undmedhorario.sd30_i_codigo = ausencias.sd06_i_undmedhorario";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql2 = "";
     if($dbwhere==""){
       if($sd06_i_codigo!=null ){
         $sql2 .= " where ausencias.sd06_i_codigo = $sd06_i_codigo "; 
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
   function sql_query_file ( $sd06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ausencias ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd06_i_codigo!=null ){
         $sql2 .= " where ausencias.sd06_i_codigo = $sd06_i_codigo "; 
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

   function sql_query_especmedico ( $sd06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ausencias ";
     $sql .= "   inner join especmedico  on  especmedico.sd27_i_codigo = ausencias.sd06_i_especmed";
     $sql2 = "";
     if($dbwhere==""){
       if($sd06_i_codigo!=null ){
         $sql2 .= " where ausencias.sd06_i_codigo = $sd06_i_codigo "; 
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