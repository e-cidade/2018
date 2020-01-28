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
//CLASSE DA ENTIDADE feriado
class cl_feriado { 
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
   var $ed54_i_codigo = 0; 
   var $ed54_i_calendario = 0; 
   var $ed54_c_descr = null; 
   var $ed54_c_diasemana = null; 
   var $ed54_d_data_dia = null; 
   var $ed54_d_data_mes = null; 
   var $ed54_d_data_ano = null; 
   var $ed54_d_data = null; 
   var $ed54_c_dialetivo = null; 
   var $ed54_i_evento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed54_i_codigo = int8 = Código 
                 ed54_i_calendario = int8 = Calendário 
                 ed54_c_descr = char(30) = Descrição 
                 ed54_c_diasemana = char(10) = Dia da Semana 
                 ed54_d_data = date = Data do Feriado 
                 ed54_c_dialetivo = char(1) = Dia Letivo 
                 ed54_i_evento = int8 = Tipo de Evento 
                 ";
   //funcao construtor da classe 
   function cl_feriado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("feriado"); 
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
       $this->ed54_i_codigo = ($this->ed54_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_i_codigo"]:$this->ed54_i_codigo);
       $this->ed54_i_calendario = ($this->ed54_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_i_calendario"]:$this->ed54_i_calendario);
       $this->ed54_c_descr = ($this->ed54_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_c_descr"]:$this->ed54_c_descr);
       $this->ed54_c_diasemana = ($this->ed54_c_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_c_diasemana"]:$this->ed54_c_diasemana);
       if($this->ed54_d_data == ""){
         $this->ed54_d_data_dia = ($this->ed54_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_d_data_dia"]:$this->ed54_d_data_dia);
         $this->ed54_d_data_mes = ($this->ed54_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_d_data_mes"]:$this->ed54_d_data_mes);
         $this->ed54_d_data_ano = ($this->ed54_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_d_data_ano"]:$this->ed54_d_data_ano);
         if($this->ed54_d_data_dia != ""){
            $this->ed54_d_data = $this->ed54_d_data_ano."-".$this->ed54_d_data_mes."-".$this->ed54_d_data_dia;
         }
       }
       $this->ed54_c_dialetivo = ($this->ed54_c_dialetivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_c_dialetivo"]:$this->ed54_c_dialetivo);
       $this->ed54_i_evento = ($this->ed54_i_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_i_evento"]:$this->ed54_i_evento);
     }else{
       $this->ed54_i_codigo = ($this->ed54_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed54_i_codigo"]:$this->ed54_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed54_i_codigo){ 
      $this->atualizacampos();
     if($this->ed54_i_calendario == null ){ 
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed54_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed54_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed54_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed54_d_data == null ){ 
       $this->erro_sql = " Campo Data do Feriado nao Informado.";
       $this->erro_campo = "ed54_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed54_c_dialetivo == null ){ 
       $this->erro_sql = " Campo Dia Letivo nao Informado.";
       $this->erro_campo = "ed54_c_dialetivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed54_i_evento == null ){ 
       $this->erro_sql = " Campo Tipo de Evento nao Informado.";
       $this->erro_campo = "ed54_i_evento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed54_i_codigo == "" || $ed54_i_codigo == null ){
       $result = db_query("select nextval('feriado_ed54_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: feriado_ed54_i_codigo_seq do campo: ed54_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed54_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from feriado_ed54_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed54_i_codigo)){
         $this->erro_sql = " Campo ed54_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed54_i_codigo = $ed54_i_codigo; 
       }
     }
     if(($this->ed54_i_codigo == null) || ($this->ed54_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed54_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into feriado(
                                       ed54_i_codigo 
                                      ,ed54_i_calendario 
                                      ,ed54_c_descr 
                                      ,ed54_c_diasemana 
                                      ,ed54_d_data 
                                      ,ed54_c_dialetivo 
                                      ,ed54_i_evento 
                       )
                values (
                                $this->ed54_i_codigo 
                               ,$this->ed54_i_calendario 
                               ,'$this->ed54_c_descr' 
                               ,'$this->ed54_c_diasemana' 
                               ,".($this->ed54_d_data == "null" || $this->ed54_d_data == ""?"null":"'".$this->ed54_d_data."'")." 
                               ,'$this->ed54_c_dialetivo' 
                               ,$this->ed54_i_evento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Feriados ($this->ed54_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Feriados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Feriados ($this->ed54_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed54_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed54_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008341,'$this->ed54_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010058,1008341,'','".AddSlashes(pg_result($resaco,0,'ed54_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008342,'','".AddSlashes(pg_result($resaco,0,'ed54_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008343,'','".AddSlashes(pg_result($resaco,0,'ed54_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008353,'','".AddSlashes(pg_result($resaco,0,'ed54_c_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008344,'','".AddSlashes(pg_result($resaco,0,'ed54_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008345,'','".AddSlashes(pg_result($resaco,0,'ed54_c_dialetivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010058,1008961,'','".AddSlashes(pg_result($resaco,0,'ed54_i_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed54_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update feriado set ";
     $virgula = "";
     if(trim($this->ed54_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_codigo"])){ 
       $sql  .= $virgula." ed54_i_codigo = $this->ed54_i_codigo ";
       $virgula = ",";
       if(trim($this->ed54_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed54_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed54_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_calendario"])){ 
       $sql  .= $virgula." ed54_i_calendario = $this->ed54_i_calendario ";
       $virgula = ",";
       if(trim($this->ed54_i_calendario) == null ){ 
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed54_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed54_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_descr"])){ 
       $sql  .= $virgula." ed54_c_descr = '$this->ed54_c_descr' ";
       $virgula = ",";
       if(trim($this->ed54_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed54_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed54_c_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_diasemana"])){ 
       $sql  .= $virgula." ed54_c_diasemana = '$this->ed54_c_diasemana' ";
       $virgula = ",";
     }
     if(trim($this->ed54_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed54_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed54_d_data = '$this->ed54_d_data' ";
       $virgula = ",";
       if(trim($this->ed54_d_data) == null ){ 
         $this->erro_sql = " Campo Data do Feriado nao Informado.";
         $this->erro_campo = "ed54_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_d_data_dia"])){ 
         $sql  .= $virgula." ed54_d_data = null ";
         $virgula = ",";
         if(trim($this->ed54_d_data) == null ){ 
           $this->erro_sql = " Campo Data do Feriado nao Informado.";
           $this->erro_campo = "ed54_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed54_c_dialetivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_dialetivo"])){ 
       $sql  .= $virgula." ed54_c_dialetivo = '$this->ed54_c_dialetivo' ";
       $virgula = ",";
       if(trim($this->ed54_c_dialetivo) == null ){ 
         $this->erro_sql = " Campo Dia Letivo nao Informado.";
         $this->erro_campo = "ed54_c_dialetivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed54_i_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_evento"])){ 
       $sql  .= $virgula." ed54_i_evento = $this->ed54_i_evento ";
       $virgula = ",";
       if(trim($this->ed54_i_evento) == null ){ 
         $this->erro_sql = " Campo Tipo de Evento nao Informado.";
         $this->erro_campo = "ed54_i_evento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed54_i_codigo!=null){
       $sql .= " ed54_i_codigo = $this->ed54_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed54_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008341,'$this->ed54_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008341,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_i_codigo'))."','$this->ed54_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_calendario"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008342,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_i_calendario'))."','$this->ed54_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008343,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_c_descr'))."','$this->ed54_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_diasemana"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008353,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_c_diasemana'))."','$this->ed54_c_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008344,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_d_data'))."','$this->ed54_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_c_dialetivo"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008345,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_c_dialetivo'))."','$this->ed54_c_dialetivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed54_i_evento"]))
           $resac = db_query("insert into db_acount values($acount,1010058,1008961,'".AddSlashes(pg_result($resaco,$conresaco,'ed54_i_evento'))."','$this->ed54_i_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Feriados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed54_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Feriados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed54_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed54_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed54_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed54_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008341,'$ed54_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010058,1008341,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008342,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008343,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008353,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_c_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008344,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008345,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_c_dialetivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010058,1008961,'','".AddSlashes(pg_result($resaco,$iresaco,'ed54_i_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from feriado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed54_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed54_i_codigo = $ed54_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Feriados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed54_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Feriados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed54_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed54_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:feriado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed54_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from feriado ";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = feriado.ed54_i_calendario";
     $sql .= "      inner join evento  on  evento.ed96_i_codigo = feriado.ed54_i_evento";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql2 = "";
     if($dbwhere==""){
       if($ed54_i_codigo!=null ){
         $sql2 .= " where feriado.ed54_i_codigo = $ed54_i_codigo "; 
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
   function sql_query_file ( $ed54_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from feriado ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed54_i_codigo!=null ){
         $sql2 .= " where feriado.ed54_i_codigo = $ed54_i_codigo "; 
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