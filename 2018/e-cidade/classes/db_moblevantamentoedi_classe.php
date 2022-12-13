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

//MODULO: cadastro
//CLASSE DA ENTIDADE moblevantamentoedi
class cl_moblevantamentoedi { 
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
   var $j96_sequen = 0; 
   var $j96_codimporta = 0; 
   var $j96_matric = 0; 
   var $j96_codigo = 0; 
   var $j96_numero = null; 
   var $j96_compl = null; 
   var $j96_paredes = 0; 
   var $j96_cobertura = 0; 
   var $j96_revexterno = 0; 
   var $j96_esquadrias = 0; 
   var $j96_forro = 0; 
   var $j96_pintura = 0; 
   var $j96_piso = 0; 
   var $j96_revinterno = 0; 
   var $j96_instsanitario = 0; 
   var $j96_insteletrica = 0; 
   var $j96_idade = 0; 
   var $j96_tipoconstr = 0; 
   var $j96_subtitulo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j96_sequen = int4 = Sequencial 
                 j96_codimporta = int4 = Código Importação 
                 j96_matric = int4 = Matrícula 
                 j96_codigo = int4 = Logradouro 
                 j96_numero = varchar(10) = Número 
                 j96_compl = varchar(50) = Complemento 
                 j96_paredes = int4 = Paredes 
                 j96_cobertura = int4 = Cobertura 
                 j96_revexterno = int4 = Revest. Externo 
                 j96_esquadrias = int4 = Esquadrias 
                 j96_forro = int4 = Forro 
                 j96_pintura = int4 = Pintura 
                 j96_piso = int4 = Piso 
                 j96_revinterno = int4 = Revest. Interno 
                 j96_instsanitario = int4 = Inst. Sanitária 
                 j96_insteletrica = int4 = Inst. Elétrica 
                 j96_idade = int4 = Idade Aparente 
                 j96_tipoconstr = int4 = Tipo Construção 
                 j96_subtitulo = int4 = Sub Título 
                 ";
   //funcao construtor da classe 
   function cl_moblevantamentoedi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("moblevantamentoedi"); 
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
       $this->j96_sequen = ($this->j96_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_sequen"]:$this->j96_sequen);
       $this->j96_codimporta = ($this->j96_codimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_codimporta"]:$this->j96_codimporta);
       $this->j96_matric = ($this->j96_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_matric"]:$this->j96_matric);
       $this->j96_codigo = ($this->j96_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_codigo"]:$this->j96_codigo);
       $this->j96_numero = ($this->j96_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_numero"]:$this->j96_numero);
       $this->j96_compl = ($this->j96_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_compl"]:$this->j96_compl);
       $this->j96_paredes = ($this->j96_paredes == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_paredes"]:$this->j96_paredes);
       $this->j96_cobertura = ($this->j96_cobertura == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_cobertura"]:$this->j96_cobertura);
       $this->j96_revexterno = ($this->j96_revexterno == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_revexterno"]:$this->j96_revexterno);
       $this->j96_esquadrias = ($this->j96_esquadrias == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_esquadrias"]:$this->j96_esquadrias);
       $this->j96_forro = ($this->j96_forro == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_forro"]:$this->j96_forro);
       $this->j96_pintura = ($this->j96_pintura == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_pintura"]:$this->j96_pintura);
       $this->j96_piso = ($this->j96_piso == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_piso"]:$this->j96_piso);
       $this->j96_revinterno = ($this->j96_revinterno == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_revinterno"]:$this->j96_revinterno);
       $this->j96_instsanitario = ($this->j96_instsanitario == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_instsanitario"]:$this->j96_instsanitario);
       $this->j96_insteletrica = ($this->j96_insteletrica == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_insteletrica"]:$this->j96_insteletrica);
       $this->j96_idade = ($this->j96_idade == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_idade"]:$this->j96_idade);
       $this->j96_tipoconstr = ($this->j96_tipoconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_tipoconstr"]:$this->j96_tipoconstr);
       $this->j96_subtitulo = ($this->j96_subtitulo == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_subtitulo"]:$this->j96_subtitulo);
     }else{
       $this->j96_sequen = ($this->j96_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_sequen"]:$this->j96_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($j96_sequen){ 
      $this->atualizacampos();
     if($this->j96_codimporta == null ){ 
       $this->erro_sql = " Campo Código Importação nao Informado.";
       $this->erro_campo = "j96_codimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j96_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_codigo == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "j96_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_paredes == null ){ 
       $this->erro_sql = " Campo Paredes nao Informado.";
       $this->erro_campo = "j96_paredes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_cobertura == null ){ 
       $this->erro_sql = " Campo Cobertura nao Informado.";
       $this->erro_campo = "j96_cobertura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_revexterno == null ){ 
       $this->erro_sql = " Campo Revest. Externo nao Informado.";
       $this->erro_campo = "j96_revexterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_esquadrias == null ){ 
       $this->erro_sql = " Campo Esquadrias nao Informado.";
       $this->erro_campo = "j96_esquadrias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_forro == null ){ 
       $this->erro_sql = " Campo Forro nao Informado.";
       $this->erro_campo = "j96_forro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_pintura == null ){ 
       $this->erro_sql = " Campo Pintura nao Informado.";
       $this->erro_campo = "j96_pintura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_piso == null ){ 
       $this->erro_sql = " Campo Piso nao Informado.";
       $this->erro_campo = "j96_piso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_revinterno == null ){ 
       $this->erro_sql = " Campo Revest. Interno nao Informado.";
       $this->erro_campo = "j96_revinterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_instsanitario == null ){ 
       $this->erro_sql = " Campo Inst. Sanitária nao Informado.";
       $this->erro_campo = "j96_instsanitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_insteletrica == null ){ 
       $this->erro_sql = " Campo Inst. Elétrica nao Informado.";
       $this->erro_campo = "j96_insteletrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_idade == null ){ 
       $this->erro_sql = " Campo Idade Aparente nao Informado.";
       $this->erro_campo = "j96_idade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_tipoconstr == null ){ 
       $this->erro_sql = " Campo Tipo Construção nao Informado.";
       $this->erro_campo = "j96_tipoconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_subtitulo == null ){ 
       $this->erro_sql = " Campo Sub Título nao Informado.";
       $this->erro_campo = "j96_subtitulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j96_sequen == "" || $j96_sequen == null ){
       $result = db_query("select nextval('moblevantamentoedi_j96_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: moblevantamentoedi_j96_sequen_seq do campo: j96_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j96_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from moblevantamentoedi_j96_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $j96_sequen)){
         $this->erro_sql = " Campo j96_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j96_sequen = $j96_sequen; 
       }
     }
     if(($this->j96_sequen == null) || ($this->j96_sequen == "") ){ 
       $this->erro_sql = " Campo j96_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into moblevantamentoedi(
                                       j96_sequen 
                                      ,j96_codimporta 
                                      ,j96_matric 
                                      ,j96_codigo 
                                      ,j96_numero 
                                      ,j96_compl 
                                      ,j96_paredes 
                                      ,j96_cobertura 
                                      ,j96_revexterno 
                                      ,j96_esquadrias 
                                      ,j96_forro 
                                      ,j96_pintura 
                                      ,j96_piso 
                                      ,j96_revinterno 
                                      ,j96_instsanitario 
                                      ,j96_insteletrica 
                                      ,j96_idade 
                                      ,j96_tipoconstr 
                                      ,j96_subtitulo 
                       )
                values (
                                $this->j96_sequen 
                               ,$this->j96_codimporta 
                               ,$this->j96_matric 
                               ,$this->j96_codigo 
                               ,'$this->j96_numero' 
                               ,'$this->j96_compl' 
                               ,$this->j96_paredes 
                               ,$this->j96_cobertura 
                               ,$this->j96_revexterno 
                               ,$this->j96_esquadrias 
                               ,$this->j96_forro 
                               ,$this->j96_pintura 
                               ,$this->j96_piso 
                               ,$this->j96_revinterno 
                               ,$this->j96_instsanitario 
                               ,$this->j96_insteletrica 
                               ,$this->j96_idade 
                               ,$this->j96_tipoconstr 
                               ,$this->j96_subtitulo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Constrições das Matrículas ($this->j96_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Constrições das Matrículas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Constrições das Matrículas ($this->j96_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j96_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9735,'$this->j96_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,1670,9735,'','".AddSlashes(pg_result($resaco,0,'j96_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9732,'','".AddSlashes(pg_result($resaco,0,'j96_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9710,'','".AddSlashes(pg_result($resaco,0,'j96_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9712,'','".AddSlashes(pg_result($resaco,0,'j96_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9713,'','".AddSlashes(pg_result($resaco,0,'j96_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9714,'','".AddSlashes(pg_result($resaco,0,'j96_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9715,'','".AddSlashes(pg_result($resaco,0,'j96_paredes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9716,'','".AddSlashes(pg_result($resaco,0,'j96_cobertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9717,'','".AddSlashes(pg_result($resaco,0,'j96_revexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9718,'','".AddSlashes(pg_result($resaco,0,'j96_esquadrias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9719,'','".AddSlashes(pg_result($resaco,0,'j96_forro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9720,'','".AddSlashes(pg_result($resaco,0,'j96_pintura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9721,'','".AddSlashes(pg_result($resaco,0,'j96_piso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9722,'','".AddSlashes(pg_result($resaco,0,'j96_revinterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9723,'','".AddSlashes(pg_result($resaco,0,'j96_instsanitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9724,'','".AddSlashes(pg_result($resaco,0,'j96_insteletrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9725,'','".AddSlashes(pg_result($resaco,0,'j96_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9726,'','".AddSlashes(pg_result($resaco,0,'j96_tipoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1670,9727,'','".AddSlashes(pg_result($resaco,0,'j96_subtitulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j96_sequen=null) { 
      $this->atualizacampos();
     $sql = " update moblevantamentoedi set ";
     $virgula = "";
     if(trim($this->j96_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_sequen"])){ 
       $sql  .= $virgula." j96_sequen = $this->j96_sequen ";
       $virgula = ",";
       if(trim($this->j96_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j96_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_codimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_codimporta"])){ 
       $sql  .= $virgula." j96_codimporta = $this->j96_codimporta ";
       $virgula = ",";
       if(trim($this->j96_codimporta) == null ){ 
         $this->erro_sql = " Campo Código Importação nao Informado.";
         $this->erro_campo = "j96_codimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_matric"])){ 
       $sql  .= $virgula." j96_matric = $this->j96_matric ";
       $virgula = ",";
       if(trim($this->j96_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j96_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_codigo"])){ 
       $sql  .= $virgula." j96_codigo = $this->j96_codigo ";
       $virgula = ",";
       if(trim($this->j96_codigo) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "j96_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_numero"])){ 
       $sql  .= $virgula." j96_numero = '$this->j96_numero' ";
       $virgula = ",";
     }
     if(trim($this->j96_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_compl"])){ 
       $sql  .= $virgula." j96_compl = '$this->j96_compl' ";
       $virgula = ",";
     }
     if(trim($this->j96_paredes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_paredes"])){ 
       $sql  .= $virgula." j96_paredes = $this->j96_paredes ";
       $virgula = ",";
       if(trim($this->j96_paredes) == null ){ 
         $this->erro_sql = " Campo Paredes nao Informado.";
         $this->erro_campo = "j96_paredes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_cobertura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_cobertura"])){ 
       $sql  .= $virgula." j96_cobertura = $this->j96_cobertura ";
       $virgula = ",";
       if(trim($this->j96_cobertura) == null ){ 
         $this->erro_sql = " Campo Cobertura nao Informado.";
         $this->erro_campo = "j96_cobertura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_revexterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_revexterno"])){ 
       $sql  .= $virgula." j96_revexterno = $this->j96_revexterno ";
       $virgula = ",";
       if(trim($this->j96_revexterno) == null ){ 
         $this->erro_sql = " Campo Revest. Externo nao Informado.";
         $this->erro_campo = "j96_revexterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_esquadrias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_esquadrias"])){ 
       $sql  .= $virgula." j96_esquadrias = $this->j96_esquadrias ";
       $virgula = ",";
       if(trim($this->j96_esquadrias) == null ){ 
         $this->erro_sql = " Campo Esquadrias nao Informado.";
         $this->erro_campo = "j96_esquadrias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_forro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_forro"])){ 
       $sql  .= $virgula." j96_forro = $this->j96_forro ";
       $virgula = ",";
       if(trim($this->j96_forro) == null ){ 
         $this->erro_sql = " Campo Forro nao Informado.";
         $this->erro_campo = "j96_forro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_pintura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_pintura"])){ 
       $sql  .= $virgula." j96_pintura = $this->j96_pintura ";
       $virgula = ",";
       if(trim($this->j96_pintura) == null ){ 
         $this->erro_sql = " Campo Pintura nao Informado.";
         $this->erro_campo = "j96_pintura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_piso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_piso"])){ 
       $sql  .= $virgula." j96_piso = $this->j96_piso ";
       $virgula = ",";
       if(trim($this->j96_piso) == null ){ 
         $this->erro_sql = " Campo Piso nao Informado.";
         $this->erro_campo = "j96_piso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_revinterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_revinterno"])){ 
       $sql  .= $virgula." j96_revinterno = $this->j96_revinterno ";
       $virgula = ",";
       if(trim($this->j96_revinterno) == null ){ 
         $this->erro_sql = " Campo Revest. Interno nao Informado.";
         $this->erro_campo = "j96_revinterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_instsanitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_instsanitario"])){ 
       $sql  .= $virgula." j96_instsanitario = $this->j96_instsanitario ";
       $virgula = ",";
       if(trim($this->j96_instsanitario) == null ){ 
         $this->erro_sql = " Campo Inst. Sanitária nao Informado.";
         $this->erro_campo = "j96_instsanitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_insteletrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_insteletrica"])){ 
       $sql  .= $virgula." j96_insteletrica = $this->j96_insteletrica ";
       $virgula = ",";
       if(trim($this->j96_insteletrica) == null ){ 
         $this->erro_sql = " Campo Inst. Elétrica nao Informado.";
         $this->erro_campo = "j96_insteletrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_idade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_idade"])){ 
       $sql  .= $virgula." j96_idade = $this->j96_idade ";
       $virgula = ",";
       if(trim($this->j96_idade) == null ){ 
         $this->erro_sql = " Campo Idade Aparente nao Informado.";
         $this->erro_campo = "j96_idade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_tipoconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_tipoconstr"])){ 
       $sql  .= $virgula." j96_tipoconstr = $this->j96_tipoconstr ";
       $virgula = ",";
       if(trim($this->j96_tipoconstr) == null ){ 
         $this->erro_sql = " Campo Tipo Construção nao Informado.";
         $this->erro_campo = "j96_tipoconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_subtitulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_subtitulo"])){ 
       $sql  .= $virgula." j96_subtitulo = $this->j96_subtitulo ";
       $virgula = ",";
       if(trim($this->j96_subtitulo) == null ){ 
         $this->erro_sql = " Campo Sub Título nao Informado.";
         $this->erro_campo = "j96_subtitulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j96_sequen!=null){
       $sql .= " j96_sequen = $this->j96_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j96_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9735,'$this->j96_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_sequen"]))
           $resac = db_query("insert into db_acount values($acount,1670,9735,'".AddSlashes(pg_result($resaco,$conresaco,'j96_sequen'))."','$this->j96_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_codimporta"]))
           $resac = db_query("insert into db_acount values($acount,1670,9732,'".AddSlashes(pg_result($resaco,$conresaco,'j96_codimporta'))."','$this->j96_codimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_matric"]))
           $resac = db_query("insert into db_acount values($acount,1670,9710,'".AddSlashes(pg_result($resaco,$conresaco,'j96_matric'))."','$this->j96_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1670,9712,'".AddSlashes(pg_result($resaco,$conresaco,'j96_codigo'))."','$this->j96_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_numero"]))
           $resac = db_query("insert into db_acount values($acount,1670,9713,'".AddSlashes(pg_result($resaco,$conresaco,'j96_numero'))."','$this->j96_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_compl"]))
           $resac = db_query("insert into db_acount values($acount,1670,9714,'".AddSlashes(pg_result($resaco,$conresaco,'j96_compl'))."','$this->j96_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_paredes"]))
           $resac = db_query("insert into db_acount values($acount,1670,9715,'".AddSlashes(pg_result($resaco,$conresaco,'j96_paredes'))."','$this->j96_paredes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_cobertura"]))
           $resac = db_query("insert into db_acount values($acount,1670,9716,'".AddSlashes(pg_result($resaco,$conresaco,'j96_cobertura'))."','$this->j96_cobertura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_revexterno"]))
           $resac = db_query("insert into db_acount values($acount,1670,9717,'".AddSlashes(pg_result($resaco,$conresaco,'j96_revexterno'))."','$this->j96_revexterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_esquadrias"]))
           $resac = db_query("insert into db_acount values($acount,1670,9718,'".AddSlashes(pg_result($resaco,$conresaco,'j96_esquadrias'))."','$this->j96_esquadrias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_forro"]))
           $resac = db_query("insert into db_acount values($acount,1670,9719,'".AddSlashes(pg_result($resaco,$conresaco,'j96_forro'))."','$this->j96_forro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_pintura"]))
           $resac = db_query("insert into db_acount values($acount,1670,9720,'".AddSlashes(pg_result($resaco,$conresaco,'j96_pintura'))."','$this->j96_pintura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_piso"]))
           $resac = db_query("insert into db_acount values($acount,1670,9721,'".AddSlashes(pg_result($resaco,$conresaco,'j96_piso'))."','$this->j96_piso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_revinterno"]))
           $resac = db_query("insert into db_acount values($acount,1670,9722,'".AddSlashes(pg_result($resaco,$conresaco,'j96_revinterno'))."','$this->j96_revinterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_instsanitario"]))
           $resac = db_query("insert into db_acount values($acount,1670,9723,'".AddSlashes(pg_result($resaco,$conresaco,'j96_instsanitario'))."','$this->j96_instsanitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_insteletrica"]))
           $resac = db_query("insert into db_acount values($acount,1670,9724,'".AddSlashes(pg_result($resaco,$conresaco,'j96_insteletrica'))."','$this->j96_insteletrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_idade"]))
           $resac = db_query("insert into db_acount values($acount,1670,9725,'".AddSlashes(pg_result($resaco,$conresaco,'j96_idade'))."','$this->j96_idade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_tipoconstr"]))
           $resac = db_query("insert into db_acount values($acount,1670,9726,'".AddSlashes(pg_result($resaco,$conresaco,'j96_tipoconstr'))."','$this->j96_tipoconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_subtitulo"]))
           $resac = db_query("insert into db_acount values($acount,1670,9727,'".AddSlashes(pg_result($resaco,$conresaco,'j96_subtitulo'))."','$this->j96_subtitulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Constrições das Matrículas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Constrições das Matrículas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j96_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j96_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9735,'$j96_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,1670,9735,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9732,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9710,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9712,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9713,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9714,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9715,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_paredes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9716,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_cobertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9717,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_revexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9718,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_esquadrias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9719,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_forro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9720,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_pintura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9721,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_piso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9722,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_revinterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9723,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_instsanitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9724,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_insteletrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9725,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9726,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_tipoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1670,9727,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_subtitulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from moblevantamentoedi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j96_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j96_sequen = $j96_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Constrições das Matrículas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j96_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Constrições das Matrículas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j96_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j96_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:moblevantamentoedi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j96_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamentoedi ";
     $sql .= "      inner join mobimportacao  on  mobimportacao.j95_codimporta = moblevantamentoedi.j96_codimporta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mobimportacao.j95_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j96_sequen!=null ){
         $sql2 .= " where moblevantamentoedi.j96_sequen = $j96_sequen "; 
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
   function sql_query_file ( $j96_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamentoedi ";
     $sql2 = "";
     if($dbwhere==""){
       if($j96_sequen!=null ){
         $sql2 .= " where moblevantamentoedi.j96_sequen = $j96_sequen "; 
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