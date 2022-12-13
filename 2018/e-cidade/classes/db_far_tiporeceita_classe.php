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
//CLASSE DA ENTIDADE far_tiporeceita
class cl_far_tiporeceita { 
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
   var $fa03_i_codigo = 0; 
   var $fa03_c_descr = null; 
   var $fa03_c_profissional = null; 
   var $fa03_c_posologia = null; 
   var $fa03_c_requisitante = null; 
   var $fa03_c_quant = null; 
   var $fa03_c_numeroreceita = null; 
   var $fa03_i_prescricaomedica = 0; 
   var $fa03_i_ativa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa03_i_codigo = int4 = C�digo 
                 fa03_c_descr = char(50) = Descri��o 
                 fa03_c_profissional = char(1) = Profissional 
                 fa03_c_posologia = char(1) = Posologia 
                 fa03_c_requisitante = char(1) = Requisitante 
                 fa03_c_quant = char(1) = Vias do Recibo 
                 fa03_c_numeroreceita = char(1) = N�mero receita 
                 fa03_i_prescricaomedica = int4 = Prescricao M�dica 
                 fa03_i_ativa = int4 = Ativa 
                 ";
   //funcao construtor da classe 
   function cl_far_tiporeceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_tiporeceita"); 
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
       $this->fa03_i_codigo = ($this->fa03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]:$this->fa03_i_codigo);
       $this->fa03_c_descr = ($this->fa03_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"]:$this->fa03_c_descr);
       $this->fa03_c_profissional = ($this->fa03_c_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"]:$this->fa03_c_profissional);
       $this->fa03_c_posologia = ($this->fa03_c_posologia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"]:$this->fa03_c_posologia);
       $this->fa03_c_requisitante = ($this->fa03_c_requisitante == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"]:$this->fa03_c_requisitante);
       $this->fa03_c_quant = ($this->fa03_c_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"]:$this->fa03_c_quant);
       $this->fa03_c_numeroreceita = ($this->fa03_c_numeroreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"]:$this->fa03_c_numeroreceita);
       $this->fa03_i_prescricaomedica = ($this->fa03_i_prescricaomedica == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"]:$this->fa03_i_prescricaomedica);
       $this->fa03_i_ativa = ($this->fa03_i_ativa == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"]:$this->fa03_i_ativa);
     }else{
       $this->fa03_i_codigo = ($this->fa03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]:$this->fa03_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa03_i_codigo){ 
      $this->atualizacampos();
     if($this->fa03_c_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "fa03_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_profissional == null ){ 
       $this->erro_sql = " Campo Profissional nao Informado.";
       $this->erro_campo = "fa03_c_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_posologia == null ){ 
       $this->erro_sql = " Campo Posologia nao Informado.";
       $this->erro_campo = "fa03_c_posologia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_requisitante == null ){ 
       $this->erro_sql = " Campo Requisitante nao Informado.";
       $this->erro_campo = "fa03_c_requisitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_numeroreceita == null ){ 
       $this->erro_sql = " Campo N�mero receita nao Informado.";
       $this->erro_campo = "fa03_c_numeroreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_i_prescricaomedica == null ){ 
       $this->fa03_i_prescricaomedica = "null";
     }
     if($this->fa03_i_ativa == null ){ 
       $this->erro_sql = " Campo Ativa nao Informado.";
       $this->erro_campo = "fa03_i_ativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa03_i_codigo == "" || $fa03_i_codigo == null ){
       $result = db_query("select nextval('fartiporeceita_fa03_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fartiporeceita_fa03_i_codigo_seq do campo: fa03_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa03_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from fartiporeceita_fa03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa03_i_codigo)){
         $this->erro_sql = " Campo fa03_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa03_i_codigo = $fa03_i_codigo; 
       }
     }
     if(($this->fa03_i_codigo == null) || ($this->fa03_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa03_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_tiporeceita(
                                       fa03_i_codigo 
                                      ,fa03_c_descr 
                                      ,fa03_c_profissional 
                                      ,fa03_c_posologia 
                                      ,fa03_c_requisitante 
                                      ,fa03_c_quant 
                                      ,fa03_c_numeroreceita 
                                      ,fa03_i_prescricaomedica 
                                      ,fa03_i_ativa 
                       )
                values (
                                $this->fa03_i_codigo 
                               ,'$this->fa03_c_descr' 
                               ,'$this->fa03_c_profissional' 
                               ,'$this->fa03_c_posologia' 
                               ,'$this->fa03_c_requisitante' 
                               ,'$this->fa03_c_quant' 
                               ,'$this->fa03_c_numeroreceita' 
                               ,$this->fa03_i_prescricaomedica 
                               ,$this->fa03_i_ativa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_tiporeceita ($this->fa03_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_tiporeceita j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_tiporeceita ($this->fa03_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa03_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12126,'$this->fa03_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2105,12126,'','".AddSlashes(pg_result($resaco,0,'fa03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12127,'','".AddSlashes(pg_result($resaco,0,'fa03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12128,'','".AddSlashes(pg_result($resaco,0,'fa03_c_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12129,'','".AddSlashes(pg_result($resaco,0,'fa03_c_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12130,'','".AddSlashes(pg_result($resaco,0,'fa03_c_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12168,'','".AddSlashes(pg_result($resaco,0,'fa03_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,12132,'','".AddSlashes(pg_result($resaco,0,'fa03_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,14054,'','".AddSlashes(pg_result($resaco,0,'fa03_i_prescricaomedica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2105,16770,'','".AddSlashes(pg_result($resaco,0,'fa03_i_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa03_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_tiporeceita set ";
     $virgula = "";
     if(trim($this->fa03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"])){ 
       $sql  .= $virgula." fa03_i_codigo = $this->fa03_i_codigo ";
       $virgula = ",";
       if(trim($this->fa03_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "fa03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"])){ 
       $sql  .= $virgula." fa03_c_descr = '$this->fa03_c_descr' ";
       $virgula = ",";
       if(trim($this->fa03_c_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "fa03_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"])){ 
       $sql  .= $virgula." fa03_c_profissional = '$this->fa03_c_profissional' ";
       $virgula = ",";
       if(trim($this->fa03_c_profissional) == null ){ 
         $this->erro_sql = " Campo Profissional nao Informado.";
         $this->erro_campo = "fa03_c_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_posologia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"])){ 
       $sql  .= $virgula." fa03_c_posologia = '$this->fa03_c_posologia' ";
       $virgula = ",";
       if(trim($this->fa03_c_posologia) == null ){ 
         $this->erro_sql = " Campo Posologia nao Informado.";
         $this->erro_campo = "fa03_c_posologia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_requisitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"])){ 
       $sql  .= $virgula." fa03_c_requisitante = '$this->fa03_c_requisitante' ";
       $virgula = ",";
       if(trim($this->fa03_c_requisitante) == null ){ 
         $this->erro_sql = " Campo Requisitante nao Informado.";
         $this->erro_campo = "fa03_c_requisitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"])){ 
       $sql  .= $virgula." fa03_c_quant = '$this->fa03_c_quant' ";
       $virgula = ",";
     }
     if(trim($this->fa03_c_numeroreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"])){ 
       $sql  .= $virgula." fa03_c_numeroreceita = '$this->fa03_c_numeroreceita' ";
       $virgula = ",";
       if(trim($this->fa03_c_numeroreceita) == null ){ 
         $this->erro_sql = " Campo N�mero receita nao Informado.";
         $this->erro_campo = "fa03_c_numeroreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_i_prescricaomedica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"])){ 
        if(trim($this->fa03_i_prescricaomedica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"])){ 
           $this->fa03_i_prescricaomedica = "null" ; 
        } 
       $sql  .= $virgula." fa03_i_prescricaomedica = $this->fa03_i_prescricaomedica ";
       $virgula = ",";
     }
     if(trim($this->fa03_i_ativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"])){ 
       $sql  .= $virgula." fa03_i_ativa = $this->fa03_i_ativa ";
       $virgula = ",";
       if(trim($this->fa03_i_ativa) == null ){ 
         $this->erro_sql = " Campo Ativa nao Informado.";
         $this->erro_campo = "fa03_i_ativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa03_i_codigo!=null){
       $sql .= " fa03_i_codigo = $this->fa03_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa03_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12126,'$this->fa03_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]) || $this->fa03_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2105,12126,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_codigo'))."','$this->fa03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"]) || $this->fa03_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2105,12127,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_descr'))."','$this->fa03_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"]) || $this->fa03_c_profissional != "")
           $resac = db_query("insert into db_acount values($acount,2105,12128,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_profissional'))."','$this->fa03_c_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"]) || $this->fa03_c_posologia != "")
           $resac = db_query("insert into db_acount values($acount,2105,12129,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_posologia'))."','$this->fa03_c_posologia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"]) || $this->fa03_c_requisitante != "")
           $resac = db_query("insert into db_acount values($acount,2105,12130,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_requisitante'))."','$this->fa03_c_requisitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"]) || $this->fa03_c_quant != "")
           $resac = db_query("insert into db_acount values($acount,2105,12168,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_quant'))."','$this->fa03_c_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"]) || $this->fa03_c_numeroreceita != "")
           $resac = db_query("insert into db_acount values($acount,2105,12132,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_numeroreceita'))."','$this->fa03_c_numeroreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"]) || $this->fa03_i_prescricaomedica != "")
           $resac = db_query("insert into db_acount values($acount,2105,14054,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_prescricaomedica'))."','$this->fa03_i_prescricaomedica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"]) || $this->fa03_i_ativa != "")
           $resac = db_query("insert into db_acount values($acount,2105,16770,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_ativa'))."','$this->fa03_i_ativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_tiporeceita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_tiporeceita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa03_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa03_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12126,'$fa03_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2105,12126,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12127,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12128,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12129,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12130,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12168,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12132,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,14054,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_prescricaomedica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,16770,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_tiporeceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa03_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa03_i_codigo = $fa03_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_tiporeceita nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa03_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_tiporeceita nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa03_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa03_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_tiporeceita";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_tiporeceita ";
     $sql .= "      left  join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_tiporeceita.fa03_i_prescricaomedica";
     $sql2 = "";
     if($dbwhere==""){
       if($fa03_i_codigo!=null ){
         $sql2 .= " where far_tiporeceita.fa03_i_codigo = $fa03_i_codigo "; 
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
   function sql_query_file ( $fa03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_tiporeceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa03_i_codigo!=null ){
         $sql2 .= " where far_tiporeceita.fa03_i_codigo = $fa03_i_codigo "; 
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