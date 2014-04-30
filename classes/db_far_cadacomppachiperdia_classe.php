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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_cadacomppachiperdia
class cl_far_cadacomppachiperdia { 
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
   var $fa50_i_codigo = 0; 
   var $fa50_i_triagem = 0; 
   var $fa50_i_cgsund = 0; 
   var $fa50_i_outrosmedicamentos = 0; 
   var $fa50_i_tipo = 0; 
   var $fa50_i_naomedicamentoso = 0; 
   var $fa50_i_diabetesacomp = 0; 
   var $fa50_i_hipertensaoacomp = 0; 
   var $fa50_i_exportado = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa50_i_codigo = int4 = Código 
                 fa50_i_triagem = int4 = Triagem 
                 fa50_i_cgsund = int4 = CGS 
                 fa50_i_outrosmedicamentos = int4 = Outros medicamentos 
                 fa50_i_tipo = int4 = Tipo 
                 fa50_i_naomedicamentoso = int4 = Não medicamentoso 
                 fa50_i_diabetesacomp = int4 = Diabetes 
                 fa50_i_hipertensaoacomp = int4 = Hipertensão 
                 fa50_i_exportado = int4 = Exportado 
                 ";
   //funcao construtor da classe 
   function cl_far_cadacomppachiperdia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_cadacomppachiperdia"); 
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
       $this->fa50_i_codigo = ($this->fa50_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_codigo"]:$this->fa50_i_codigo);
       $this->fa50_i_triagem = ($this->fa50_i_triagem == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_triagem"]:$this->fa50_i_triagem);
       $this->fa50_i_cgsund = ($this->fa50_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_cgsund"]:$this->fa50_i_cgsund);
       $this->fa50_i_outrosmedicamentos = ($this->fa50_i_outrosmedicamentos == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_outrosmedicamentos"]:$this->fa50_i_outrosmedicamentos);
       $this->fa50_i_tipo = ($this->fa50_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_tipo"]:$this->fa50_i_tipo);
       $this->fa50_i_naomedicamentoso = ($this->fa50_i_naomedicamentoso == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_naomedicamentoso"]:$this->fa50_i_naomedicamentoso);
       $this->fa50_i_diabetesacomp = ($this->fa50_i_diabetesacomp == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_diabetesacomp"]:$this->fa50_i_diabetesacomp);
       $this->fa50_i_hipertensaoacomp = ($this->fa50_i_hipertensaoacomp == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_hipertensaoacomp"]:$this->fa50_i_hipertensaoacomp);
       $this->fa50_i_exportado = ($this->fa50_i_exportado == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_exportado"]:$this->fa50_i_exportado);
     }else{
       $this->fa50_i_codigo = ($this->fa50_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa50_i_codigo"]:$this->fa50_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa50_i_codigo){ 
      $this->atualizacampos();
     if($this->fa50_i_triagem == null ){ 
       $this->erro_sql = " Campo Triagem nao Informado.";
       $this->erro_campo = "fa50_i_triagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "fa50_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_outrosmedicamentos == null ){ 
       $this->erro_sql = " Campo Outros medicamentos nao Informado.";
       $this->erro_campo = "fa50_i_outrosmedicamentos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "fa50_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_naomedicamentoso == null ){ 
       $this->erro_sql = " Campo Não medicamentoso nao Informado.";
       $this->erro_campo = "fa50_i_naomedicamentoso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_diabetesacomp == null ){ 
       $this->erro_sql = " Campo Diabetes nao Informado.";
       $this->erro_campo = "fa50_i_diabetesacomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_hipertensaoacomp == null ){ 
       $this->erro_sql = " Campo Hipertensão nao Informado.";
       $this->erro_campo = "fa50_i_hipertensaoacomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa50_i_exportado == null ){ 
       $this->erro_sql = " Campo Exportado nao Informado.";
       $this->erro_campo = "fa50_i_exportado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa50_i_codigo == "" || $fa50_i_codigo == null ){
       $result = db_query("select nextval('far_cadacomppachiperdia_fa50_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_cadacomppachiperdia_fa50_i_codigo_seq do campo: fa50_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa50_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_cadacomppachiperdia_fa50_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa50_i_codigo)){
         $this->erro_sql = " Campo fa50_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa50_i_codigo = $fa50_i_codigo; 
       }
     }
     if(($this->fa50_i_codigo == null) || ($this->fa50_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa50_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_cadacomppachiperdia(
                                       fa50_i_codigo 
                                      ,fa50_i_triagem 
                                      ,fa50_i_cgsund 
                                      ,fa50_i_outrosmedicamentos 
                                      ,fa50_i_tipo 
                                      ,fa50_i_naomedicamentoso 
                                      ,fa50_i_diabetesacomp 
                                      ,fa50_i_hipertensaoacomp 
                                      ,fa50_i_exportado 
                       )
                values (
                                $this->fa50_i_codigo 
                               ,$this->fa50_i_triagem 
                               ,$this->fa50_i_cgsund 
                               ,$this->fa50_i_outrosmedicamentos 
                               ,$this->fa50_i_tipo 
                               ,$this->fa50_i_naomedicamentoso 
                               ,$this->fa50_i_diabetesacomp 
                               ,$this->fa50_i_hipertensaoacomp 
                               ,$this->fa50_i_exportado 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_cadacomppachiperdia ($this->fa50_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_cadacomppachiperdia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_cadacomppachiperdia ($this->fa50_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa50_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa50_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17251,'$this->fa50_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3052,17251,'','".AddSlashes(pg_result($resaco,0,'fa50_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17252,'','".AddSlashes(pg_result($resaco,0,'fa50_i_triagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17253,'','".AddSlashes(pg_result($resaco,0,'fa50_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17254,'','".AddSlashes(pg_result($resaco,0,'fa50_i_outrosmedicamentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17255,'','".AddSlashes(pg_result($resaco,0,'fa50_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17256,'','".AddSlashes(pg_result($resaco,0,'fa50_i_naomedicamentoso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17257,'','".AddSlashes(pg_result($resaco,0,'fa50_i_diabetesacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17258,'','".AddSlashes(pg_result($resaco,0,'fa50_i_hipertensaoacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3052,17259,'','".AddSlashes(pg_result($resaco,0,'fa50_i_exportado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa50_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_cadacomppachiperdia set ";
     $virgula = "";
     if(trim($this->fa50_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_codigo"])){ 
       $sql  .= $virgula." fa50_i_codigo = $this->fa50_i_codigo ";
       $virgula = ",";
       if(trim($this->fa50_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa50_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_triagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_triagem"])){ 
       $sql  .= $virgula." fa50_i_triagem = $this->fa50_i_triagem ";
       $virgula = ",";
       if(trim($this->fa50_i_triagem) == null ){ 
         $this->erro_sql = " Campo Triagem nao Informado.";
         $this->erro_campo = "fa50_i_triagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_cgsund"])){ 
       $sql  .= $virgula." fa50_i_cgsund = $this->fa50_i_cgsund ";
       $virgula = ",";
       if(trim($this->fa50_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "fa50_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_outrosmedicamentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_outrosmedicamentos"])){ 
       $sql  .= $virgula." fa50_i_outrosmedicamentos = $this->fa50_i_outrosmedicamentos ";
       $virgula = ",";
       if(trim($this->fa50_i_outrosmedicamentos) == null ){ 
         $this->erro_sql = " Campo Outros medicamentos nao Informado.";
         $this->erro_campo = "fa50_i_outrosmedicamentos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_tipo"])){ 
       $sql  .= $virgula." fa50_i_tipo = $this->fa50_i_tipo ";
       $virgula = ",";
       if(trim($this->fa50_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "fa50_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_naomedicamentoso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_naomedicamentoso"])){ 
       $sql  .= $virgula." fa50_i_naomedicamentoso = $this->fa50_i_naomedicamentoso ";
       $virgula = ",";
       if(trim($this->fa50_i_naomedicamentoso) == null ){ 
         $this->erro_sql = " Campo Não medicamentoso nao Informado.";
         $this->erro_campo = "fa50_i_naomedicamentoso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_diabetesacomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_diabetesacomp"])){ 
       $sql  .= $virgula." fa50_i_diabetesacomp = $this->fa50_i_diabetesacomp ";
       $virgula = ",";
       if(trim($this->fa50_i_diabetesacomp) == null ){ 
         $this->erro_sql = " Campo Diabetes nao Informado.";
         $this->erro_campo = "fa50_i_diabetesacomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_hipertensaoacomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_hipertensaoacomp"])){ 
       $sql  .= $virgula." fa50_i_hipertensaoacomp = $this->fa50_i_hipertensaoacomp ";
       $virgula = ",";
       if(trim($this->fa50_i_hipertensaoacomp) == null ){ 
         $this->erro_sql = " Campo Hipertensão nao Informado.";
         $this->erro_campo = "fa50_i_hipertensaoacomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa50_i_exportado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_exportado"])){ 
       $sql  .= $virgula." fa50_i_exportado = $this->fa50_i_exportado ";
       $virgula = ",";
       if(trim($this->fa50_i_exportado) == null ){ 
         $this->erro_sql = " Campo Exportado nao Informado.";
         $this->erro_campo = "fa50_i_exportado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa50_i_codigo!=null){
       $sql .= " fa50_i_codigo = $this->fa50_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa50_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17251,'$this->fa50_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_codigo"]) || $this->fa50_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3052,17251,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_codigo'))."','$this->fa50_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_triagem"]) || $this->fa50_i_triagem != "")
           $resac = db_query("insert into db_acount values($acount,3052,17252,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_triagem'))."','$this->fa50_i_triagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_cgsund"]) || $this->fa50_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,3052,17253,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_cgsund'))."','$this->fa50_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_outrosmedicamentos"]) || $this->fa50_i_outrosmedicamentos != "")
           $resac = db_query("insert into db_acount values($acount,3052,17254,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_outrosmedicamentos'))."','$this->fa50_i_outrosmedicamentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_tipo"]) || $this->fa50_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3052,17255,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_tipo'))."','$this->fa50_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_naomedicamentoso"]) || $this->fa50_i_naomedicamentoso != "")
           $resac = db_query("insert into db_acount values($acount,3052,17256,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_naomedicamentoso'))."','$this->fa50_i_naomedicamentoso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_diabetesacomp"]) || $this->fa50_i_diabetesacomp != "")
           $resac = db_query("insert into db_acount values($acount,3052,17257,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_diabetesacomp'))."','$this->fa50_i_diabetesacomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_hipertensaoacomp"]) || $this->fa50_i_hipertensaoacomp != "")
           $resac = db_query("insert into db_acount values($acount,3052,17258,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_hipertensaoacomp'))."','$this->fa50_i_hipertensaoacomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa50_i_exportado"]) || $this->fa50_i_exportado != "")
           $resac = db_query("insert into db_acount values($acount,3052,17259,'".AddSlashes(pg_result($resaco,$conresaco,'fa50_i_exportado'))."','$this->fa50_i_exportado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_cadacomppachiperdia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa50_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_cadacomppachiperdia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa50_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa50_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17251,'$fa50_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3052,17251,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17252,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_triagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17253,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17254,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_outrosmedicamentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17255,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17256,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_naomedicamentoso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17257,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_diabetesacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17258,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_hipertensaoacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3052,17259,'','".AddSlashes(pg_result($resaco,$iresaco,'fa50_i_exportado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_cadacomppachiperdia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa50_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa50_i_codigo = $fa50_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_cadacomppachiperdia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa50_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_cadacomppachiperdia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa50_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa50_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_cadacomppachiperdia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa50_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_cadacomppachiperdia ";
     $sql .= "      inner join sau_triagemavulsa  on  sau_triagemavulsa.s152_i_codigo = far_cadacomppachiperdia.fa50_i_triagem";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_cadacomppachiperdia.fa50_i_cgsund";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_triagemavulsa.s152_i_login";
     $sql .= "      inner join far_cbosprofissional  on  far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = sau_triagemavulsa.s152_i_cgsund";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($fa50_i_codigo!=null ){
         $sql2 .= " where far_cadacomppachiperdia.fa50_i_codigo = $fa50_i_codigo "; 
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
   function sql_query_file ( $fa50_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_cadacomppachiperdia ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa50_i_codigo!=null ){
         $sql2 .= " where far_cadacomppachiperdia.fa50_i_codigo = $fa50_i_codigo "; 
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

   function sql_query2 ( $fa50_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_cadacomppachiperdia ";
     $sql .= "      inner join sau_triagemavulsa  on  sau_triagemavulsa.s152_i_codigo = far_cadacomppachiperdia.fa50_i_triagem";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_cadacomppachiperdia.fa50_i_cgsund";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_triagemavulsa.s152_i_login";
     $sql .= "      inner join far_cbosprofissional  on  far_cbosprofissional.fa54_i_codigo = sau_triagemavulsa.s152_i_cbosprofissional";
     $sql .= "      inner join far_cbos  on far_cbos.fa53_i_codigo = far_cbosprofissional.fa54_i_cbos";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = far_cbosprofissional.fa54_i_unidademedico";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join sau_distritosanitario  on sau_distritosanitario.s153_i_codigo = unidades.sd02_i_distrito";
     $sql .= "      inner join cgm  on cgm.z01_numcgm =  medicos.sd03_i_cgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($fa50_i_codigo!=null ){
         $sql2 .= " where far_cadacomppachiperdia.fa50_i_codigo = $fa50_i_codigo "; 
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